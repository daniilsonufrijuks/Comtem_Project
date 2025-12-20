<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Products; // Use your actual model name

class ChatController extends Controller
{
    private function extractProductKeywords($message)
    {
        // List of product-related keywords - ADDED LAPTOP KEYWORDS
        $productKeywords = [
            'product', 'products', 'buy', 'purchase', 'price', 'cost',
            'specification', 'specs', 'feature', 'features', 'stock',
            'available', 'availability', 'order', 'pc', 'computer',
            'laptop', 'laptops', 'notebook', 'notebooks', 'ultrabook',
            'macbook', 'dell', 'hp', 'lenovo', 'asus', 'acer', 'msi',
            'gaming', 'business', 'student', 'cpu', 'processor',
            'gpu', 'graphics', 'ram', 'memory', 'storage', 'ssd',
            'hard drive', 'motherboard', 'power supply', 'psu',
            'case', 'cooling', 'monitor', 'keyboard', 'mouse',
            'workstation', 'budget', 'cheap', 'expensive', 'discount'
        ];

        $message = strtolower($message);
        $foundKeywords = [];

        foreach ($productKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                $foundKeywords[] = $keyword;
            }
        }

        return $foundKeywords;
    }

    private function searchProducts($message)
    {
        // Check if Products model exists
        if (!class_exists('App\Models\Products')) {
            Log::warning('Products model not found');
            return collect(); // Return empty collection
        }

        // Extract potential product names or categories
        $searchTerms = $this->extractSearchTerms($message);

        // Also check for specific laptop-related terms
        $messageLower = strtolower($message);
        if (str_contains($messageLower, 'laptop') ||
            str_contains($messageLower, 'laptops') ||
            str_contains($messageLower, 'notebook')) {
            $searchTerms[] = 'laptop';
        }

        if (empty($searchTerms)) {
            return collect();
        }

        // Build the query - using Products model
        $query = Products::query();

        foreach ($searchTerms as $term) {
            $query->orWhere(function($q) use ($term) {
                $q->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('description', 'LIKE', "%{$term}%")
                    ->orWhere('category', 'LIKE', "%{$term}%");
            });
        }

        // Also search by category 'Laptops' if laptop-related
        if (str_contains($messageLower, 'laptop') ||
            str_contains($messageLower, 'laptops') ||
            str_contains($messageLower, 'notebook')) {
            $query->orWhere('category', 'LIKE', '%laptop%')
                ->orWhere('category', 'LIKE', '%notebook%');
        }

        Log::info('Searching products with terms: ' . implode(', ', $searchTerms));

        return $query->limit(10)->get();
    }

    private function extractSearchTerms($message)
    {
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to',
            'for', 'of', 'with', 'by', 'is', 'are', 'was', 'were', 'be',
            'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did',
            'will', 'would', 'should', 'could', 'can', 'may', 'might',
            'must', 'about', 'tell', 'me', 'your', 'store', 'shop'];

        $words = str_word_count(strtolower($message), 1);
        $terms = array_diff($words, $stopWords);

        // Filter out very short words but keep important ones
        return array_filter($terms, function($word) {
            return strlen($word) > 2 || in_array($word, ['pc', 'cpu', 'gpu', 'ram', 'ssd']);
        });
    }

    private function formatProductContext($products)
    {
        if ($products->isEmpty()) {
            Log::info('No products found for the query');
            return "I don't have any products matching your query in our database right now.";
        }

        Log::info('Found ' . $products->count() . ' products');

        $context = "Here are the products available in our store that match your query:\n\n";

        foreach ($products as $product) {
            $context .= "**Product Name:** {$product->name}\n";
            $context .= "**Category:** {$product->category}\n";

            if ($product->price) {
                $context .= "**Price:** $" . number_format($product->price, 2) . "\n";
            }

            if ($product->description) {
                $context .= "**Description:** {$product->description}\n";
            }

            $context .= "---\n";
        }

        $context .= "\nYou have direct access to this product information from the database. Use these details to answer questions about these specific products.";

        return $context;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $apiKey = config('services.deepseek.api_key');

        if (empty($apiKey) || !str_starts_with($apiKey, 'sk-')) {
            Log::error('DeepSeek API key issue');
            return response()->json([
                'reply' => 'AI service configuration error.'
            ], 500);
        }

        // Check if this is likely a product-related query
        $userMessage = $request->input('message');
        Log::info('User message: ' . $userMessage);

        $isProductQuery = !empty($this->extractProductKeywords($userMessage));
        Log::info('Is product query: ' . ($isProductQuery ? 'Yes' : 'No'));

        // Get relevant products if it's a product query
        $productContext = "";
        if ($isProductQuery) {
            $products = $this->searchProducts($userMessage);
            if ($products && $products->isNotEmpty()) {
                Log::info('Found ' . $products->count() . ' products in database');
                $productContext = $this->formatProductContext($products);
            } else {
                Log::info('No products found in database search');
            }
        }

        $client = new Client([
            'timeout' => 90,
            'verify' => false
        ]);

        try {
            $systemMessage = 'You are a helpful assistant specialized in PC components, phones, furniture for work, fps, games and computer hardware. ';
            $systemMessage .= "You are integrated with the store's database and have access to real product information. ";

            if ($productContext) {
                $systemMessage .= "Here is the current product information from our database:\n\n" .
                    $productContext . "\n\n" .
                    "IMPORTANT: You MUST use this product information to answer the user's question. " .
                    "Reference specific products by name when relevant. " .
                    "If the user asks about laptops, tell them about the specific laptops we have in stock. " .
                    "Be enthusiastic about our products and provide detailed information!";
            } else {
                $systemMessage .= "You don't have specific product information for this query. " .
                    "Ask the user to be more specific about what they're looking for, " .
                    "or suggest they ask about categories like laptops, desktops, components, etc.";
            }

            Log::info('System message sent to AI');

            $response = $client->post('https://api.deepseek.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'deepseek-chat',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemMessage
                        ],
                        [
                            'role' => 'user',
                            'content' => $userMessage
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 1000,
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            if (!isset($responseData['choices'][0]['message']['content'])) {
                Log::error('DeepSeek API unexpected response structure', $responseData);
                return response()->json([
                    'reply' => "Sorry, I couldn't process that. Please try again.",
                ], 500);
            }

            return response()->json([
                'reply' => $responseData['choices'][0]['message']['content'],
                'products_found' => $isProductQuery && isset($products) ? $products->count() : 0
            ]);

        } catch (\Exception $e) {
            Log::error('ChatController error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'reply' => 'Sorry, I am currently unable to process your request. Please try again later.'
            ], 500);
        }
    }

    // Debug method to check database products
    public function debugProducts()
    {
        try {
            $products = Products::where('category', 'LIKE', '%laptop%')
                ->orWhere('name', 'LIKE', '%laptop%')
                ->orWhere('description', 'LIKE', '%laptop%')
                ->get();

            return response()->json([
                'total_products' => Products::count(),
                'laptop_products' => $products->count(),
                'products' => $products->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'category' => $product->category,
                        'price' => $product->price,
                        'description' => substr($product->description, 0, 100) . '...'
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'model_exists' => class_exists('App\Models\Products')
            ]);
        }
    }
}
