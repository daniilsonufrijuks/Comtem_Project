<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Products;

class ChatController extends Controller
{
    private $productKeywords = [
        'product', 'products', 'buy', 'purchase', 'price', 'cost', 'sale', 'deal',
        'specification', 'specs', 'feature', 'features', 'stock',
        'available', 'availability', 'order', 'pc', 'computer',
        'laptop', 'laptops', 'notebook', 'notebooks', 'ultrabook',
        'macbook', 'dell', 'hp', 'lenovo', 'asus', 'acer', 'msi',
        'gaming', 'business', 'student', 'cpu', 'processor', 'intel', 'amd', 'core',
        'gpu', 'graphics', 'video card', 'graphics card', 'nvidia', 'radeon',
        'ram', 'memory', 'storage', 'ssd', 'hard drive', 'hdd',
        'motherboard', 'mainboard', 'mb', 'power supply', 'psu',
        'case', 'chassis', 'cooling', 'fan', 'heatsink', 'thermal',
        'monitor', 'display', 'screen', 'keyboard', 'keyboards', 'mouse', 'mice',
        'workstation', 'budget', 'cheap', 'expensive', 'discount', 'offer',
        'component', 'components', 'part', 'parts', 'hardware'
    ];

    private $categoryMappings = [
        'cpu' => [
            'keywords' => ['cpu', 'processor', 'intel', 'amd', 'core i3', 'core i5', 'core i7', 'core i9', 'ryzen'],
            'search_fields' => [
                ['category', 'LIKE', '%component%'],
                ['name', 'LIKE', '%intel%'],
                ['name', 'LIKE', '%amd%'],
                ['name', 'LIKE', '%core%'],
                ['name', 'LIKE', '%ryzen%'],
                ['description', 'LIKE', '%processor%']
            ],
            'category_name' => 'CPU/Processor'
        ],
        'gpu' => [
            'keywords' => ['gpu', 'graphics', 'video card', 'graphics card', 'nvidia', 'geforce', 'rtx', 'gtx', 'radeon'],
            'search_fields' => [
                ['category', 'LIKE', '%component%'],
                ['name', 'LIKE', '%nvidia%'],
                ['name', 'LIKE', '%geforce%'],
                ['name', 'LIKE', '%rtx%'],
                ['name', 'LIKE', '%gtx%'],
                ['name', 'LIKE', '%radeon%'],
                ['description', 'LIKE', '%graphics%']
            ],
            'category_name' => 'GPU/Graphics Card'
        ],
        'ram' => [
            'keywords' => ['ram', 'memory', 'ddr4', 'ddr5', 'memory stick'],
            'search_fields' => [
                ['category', 'LIKE', '%component%'],
                ['name', 'LIKE', '%ram%'],
                ['name', 'LIKE', '%memory%'],
                ['name', 'LIKE', '%ddr%']
            ],
            'category_name' => 'RAM/Memory'
        ],
        'storage' => [
            'keywords' => ['ssd', 'hard drive', 'hdd', 'storage', 'nvme', 'sata'],
            'search_fields' => [
                ['category', 'LIKE', '%component%'],
                ['name', 'LIKE', '%ssd%'],
                ['name', 'LIKE', '%hdd%'],
                ['name', 'LIKE', '%hard drive%'],
                ['description', 'LIKE', '%storage%']
            ],
            'category_name' => 'Storage'
        ],
        'motherboard' => [
            'keywords' => ['motherboard', 'mainboard', 'mb', 'mobo'],
            'search_fields' => [
                ['category', 'LIKE', '%component%'],
                ['name', 'LIKE', '%motherboard%'],
                ['name', 'LIKE', '%mainboard%']
            ],
            'category_name' => 'Motherboard'
        ],
        'psu' => [
            'keywords' => ['power supply', 'psu', 'power', 'watt'],
            'search_fields' => [
                ['category', 'LIKE', '%component%'],
                ['name', 'LIKE', '%power%'],
                ['name', 'LIKE', '%psu%'],
                ['description', 'LIKE', '%watt%']
            ],
            'category_name' => 'Power Supply'
        ],
        'case' => [
            'keywords' => ['case', 'chassis', 'tower', 'pc case'],
            'search_fields' => [
                ['category', 'LIKE', '%component%'],
                ['name', 'LIKE', '%case%'],
                ['name', 'LIKE', '%chassis%']
            ],
            'category_name' => 'PC Case'
        ],
        'cooling' => [
            'keywords' => ['cooling', 'fan', 'cooler', 'heatsink', 'thermal', 'airflow'],
            'search_fields' => [
                ['category', 'LIKE', '%component%'],
                ['name', 'LIKE', '%fan%'],
                ['name', 'LIKE', '%cooler%'],
                ['description', 'LIKE', '%cooling%']
            ],
            'category_name' => 'Cooling'
        ],
        'monitor' => [
            'keywords' => ['monitor', 'display', 'screen', 'lcd', 'led', '4k', '144hz'],
            'search_fields' => [
                ['category', '=', 'Monitor'],
                ['name', 'LIKE', '%monitor%'],
                ['name', 'LIKE', '%display%']
            ],
            'category_name' => 'Monitor'
        ],
        'keyboard' => [
            'keywords' => ['keyboard', 'mechanical keyboard', 'keyboards'],
            'search_fields' => [
                ['category', '=', 'Keyboard'],
                ['name', 'LIKE', '%keyboard%']
            ],
            'category_name' => 'Keyboard'
        ],
        'mouse' => [
            'keywords' => ['mouse', 'gaming mouse', 'mice'],
            'search_fields' => [
                ['category', '=', 'Mouse'],
                ['name', 'LIKE', '%mouse%']
            ],
            'category_name' => 'Mouse'
        ],
        'headphones' => [
            'keywords' => ['headphones', 'headset', 'headphone', 'headsets', 'audio'],
            'search_fields' => [
                ['category', '=', 'Headphones'],
                ['name', 'LIKE', '%headphone%'],
                ['name', 'LIKE', '%headset%']
            ],
            'category_name' => 'Headphones'
        ],
        'desktop' => [
            'keywords' => ['desktop', 'pc', 'computer', 'workstation', 'tower'],
            'search_fields' => [
                ['category', '=', 'Desktop'],
                ['name', 'LIKE', '%desktop%'],
                ['name', 'LIKE', '%pc%'],
                ['name', 'LIKE', '%computer%']
            ],
            'category_name' => 'Desktop PC'
        ],
        'laptop' => [
            'keywords' => ['laptop', 'laptops', 'notebook', 'notebooks', 'ultrabook'],
            'search_fields' => [
                ['category', '=', 'Laptop'],
                ['name', 'LIKE', '%laptop%'],
                ['name', 'LIKE', '%notebook%']
            ],
            'category_name' => 'Laptop'
        ]
    ];

    private $brandMappings = [
        'intel' => ['intel', 'core i3', 'core i5', 'core i7', 'core i9'],
        'amd' => ['amd', 'ryzen'],
        'nvidia' => ['nvidia', 'geforce', 'rtx', 'gtx'],
        'corsair' => ['corsair'],
        'kingston' => ['kingston', 'hyperx'],
        'samsung' => ['samsung'],
        'western digital' => ['western digital', 'wd'],
        'seagate' => ['seagate'],
        'asus' => ['asus', 'rog'],
        'msi' => ['msi'],
        'gigabyte' => ['gigabyte'],
        'evga' => ['evga'],
        'cooler master' => ['cooler master'],
        'noctua' => ['noctua'],
        'logitech' => ['logitech'],
        'razer' => ['razer'],
        'steelseries' => ['steelseries']
    ];

    private function extractProductKeywords($message)
    {
        $message = strtolower($message);
        $foundKeywords = [];

        foreach ($this->productKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                $foundKeywords[] = $keyword;
            }
        }

        return $foundKeywords;
    }

    private function searchProducts($message)
    {
        if (!class_exists('App\Models\Products')) {
            Log::warning('Products model not found');
            return collect();
        }

        $messageLower = strtolower($message);
        $query = Products::query();

        // Check for specific product category queries first
        $matchedCategory = $this->getMatchedCategory($messageLower);

        if ($matchedCategory) {
            // Apply category-specific search
            $this->applyCategorySearch($query, $matchedCategory, $messageLower);
        } else {
            // Generic search based on extracted terms
            $searchTerms = $this->extractSearchTerms($message);
            if (!empty($searchTerms)) {
                $this->applyGenericSearch($query, $searchTerms);
            }
        }

        // Always search for brands mentioned in the query
        $this->applyBrandSearch($query, $messageLower);

        // Apply price range filters if mentioned
        $this->applyPriceFilters($query, $messageLower);

        Log::info('Searching products for query: ' . substr($message, 0, 100));

        return $query->distinct()->limit(15)->get();
    }

    private function getMatchedCategory($messageLower)
    {
        foreach ($this->categoryMappings as $categoryKey => $categoryData) {
            foreach ($categoryData['keywords'] as $keyword) {
                if (str_contains($messageLower, $keyword)) {
                    return $categoryKey;
                }
            }
        }
        return null;
    }

    private function applyCategorySearch($query, $categoryKey, $messageLower)
    {
        $categoryData = $this->categoryMappings[$categoryKey];

        $query->where(function($q) use ($categoryData, $messageLower) {
            foreach ($categoryData['search_fields'] as $field) {
                if (count($field) === 3) {
                    [$column, $operator, $value] = $field;
                    $q->orWhere($column, $operator, $value);
                }
            }

            // Also search in description for category keywords
            foreach ($categoryData['keywords'] as $keyword) {
                if (str_contains($messageLower, $keyword)) {
                    $q->orWhere('description', 'LIKE', "%{$keyword}%");
                }
            }
        });
    }

    private function applyGenericSearch($query, $searchTerms)
    {
        $query->where(function($q) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $q->orWhere('name', 'LIKE', "%{$term}%")
                    ->orWhere('description', 'LIKE', "%{$term}%")
                    ->orWhere('category', 'LIKE', "%{$term}%");
            }
        });
    }

    private function applyBrandSearch($query, $messageLower)
    {
        foreach ($this->brandMappings as $brandName => $brandKeywords) {
            foreach ($brandKeywords as $keyword) {
                if (str_contains($messageLower, $keyword)) {
                    $query->orWhere(function($q) use ($brandName, $keyword) {
                        $q->where('name', 'LIKE', "%{$brandName}%")
                            ->orWhere('name', 'LIKE', "%{$keyword}%")
                            ->orWhere('description', 'LIKE', "%{$brandName}%")
                            ->orWhere('description', 'LIKE', "%{$keyword}%");
                    });
                    break;
                }
            }
        }
    }

    private function applyPriceFilters($query, $messageLower)
    {
        // Check for budget/price range mentions
        if (str_contains($messageLower, 'cheap') || str_contains($messageLower, 'budget') || str_contains($messageLower, 'low cost')) {
            $query->where('price', '<=', 100);
        } elseif (str_contains($messageLower, 'mid range') || str_contains($messageLower, 'mid-range')) {
            $query->whereBetween('price', [100, 300]);
        } elseif (str_contains($messageLower, 'high end') || str_contains($messageLower, 'expensive') || str_contains($messageLower, 'premium')) {
            $query->where('price', '>=', 300);
        }

        // Look for specific price mentions like "under $200"
        if (preg_match('/under\s+\$?(\d+)/i', $messageLower, $matches)) {
            $query->where('price', '<=', $matches[1]);
        }

        if (preg_match('/over\s+\$?(\d+)/i', $messageLower, $matches)) {
            $query->where('price', '>=', $matches[1]);
        }
    }

    private function extractSearchTerms($message)
    {
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to',
            'for', 'of', 'with', 'by', 'is', 'are', 'was', 'were', 'be',
            'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did',
            'will', 'would', 'should', 'could', 'can', 'may', 'might',
            'must', 'about', 'tell', 'me', 'your', 'store', 'shop',
            'want', 'looking', 'need', 'find', 'show', 'give'];

        $words = str_word_count(strtolower($message), 1);
        $terms = array_diff($words, $stopWords);

        return array_filter($terms, function($word) {
            return strlen($word) > 2 || in_array($word, ['pc', 'cpu', 'gpu', 'ram', 'ssd', 'hdd', 'psu', 'mb']);
        });
    }

    private function formatProductContext($products, $userMessage)
    {
        if ($products->isEmpty()) {
            Log::info('No products found for the query');

            // Provide helpful suggestions based on the query
            $messageLower = strtolower($userMessage);
            $suggestions = [];

            foreach ($this->categoryMappings as $categoryKey => $categoryData) {
                foreach ($categoryData['keywords'] as $keyword) {
                    if (str_contains($messageLower, $keyword)) {
                        $suggestions[] = "We might have {$categoryData['category_name']} products available. Please check our full catalog.";
                        break;
                    }
                }
            }

            if (empty($suggestions)) {
                $suggestions[] = "We have a wide range of PC components including CPUs, GPUs, RAM, and storage.";
            }

            return "I couldn't find exact products matching your search. " . implode(' ', $suggestions) .
                " You can ask about specific components like 'Intel processors', 'Nvidia graphics cards', or 'SSD storage'.";
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
                // Clean up description - remove extra spaces and newlines
                $cleanDescription = trim(preg_replace('/\s+/', ' ', $product->description));
                $context .= "**Description:** {$cleanDescription}\n";
            }

            $context .= "---\n";
        }

        $context .= "\nYou have direct access to these real products from our database. ";
        $context .= "Use this information to answer the user's question accurately. ";
        $context .= "If the user asks about specifications, features, or recommendations, ";
        $context .= "refer to these specific products and their details.";

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

        $userMessage = $request->input('message');
        Log::info('User message: ' . $userMessage);

        $isProductQuery = !empty($this->extractProductKeywords($userMessage));
        Log::info('Is product query: ' . ($isProductQuery ? 'Yes' : 'No'));

        $productContext = "";
        $products = collect();

        if ($isProductQuery) {
            $products = $this->searchProducts($userMessage);
            if ($products->isNotEmpty()) {
                Log::info('Found ' . $products->count() . ' products in database');
                $productContext = $this->formatProductContext($products, $userMessage);
            } else {
                Log::info('No products found in database search');
                // Still provide context even if no products found
                $productContext = $this->formatProductContext($products, $userMessage);
            }
        }

        $client = new Client([
            'timeout' => 90,
            'verify' => false
        ]);

        try {
            $systemMessage = 'You are a helpful assistant specialized in PC components, computer hardware, and electronics. ';
            $systemMessage .= "You are integrated with the store's database and have access to real product information. ";
            $systemMessage .= "You can recommend products, compare specifications, and help users make purchasing decisions. ";
            $systemMessage .= "Be specific, technical, and helpful when discussing products.\n\n";

            if ($productContext) {
                $systemMessage .= $productContext . "\n\n";
                $systemMessage .= "IMPORTANT INSTRUCTIONS:\n";
                $systemMessage .= "1. ALWAYS reference specific products by name when they match the user's query\n";
                $systemMessage .= "2. Mention prices when available\n";
                $systemMessage .= "3. Highlight key specifications from the descriptions\n";
                $systemMessage .= "4. If the user asks for recommendations, suggest the most relevant products from the list above\n";
                $systemMessage .= "5. If we don't have exactly what they're looking for, suggest similar alternatives\n";
                $systemMessage .= "6. Be enthusiastic and knowledgeable about our products!\n";
            } else {
                $systemMessage .= "The user is asking a general question. Help them with computer hardware knowledge, ";
                $systemMessage .= "recommendations, or suggest they ask about specific products we might carry like CPUs, GPUs, RAM, storage, or other components.";
            }

            Log::info('System message length: ' . strlen($systemMessage));

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
                    'max_tokens' => 1500,
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
                'products_found' => $products->count(),
                'is_product_query' => $isProductQuery
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

    // Helper method to get search suggestions for debugging
    public function getSearchableCategories()
    {
        $categories = [];
        foreach ($this->categoryMappings as $key => $data) {
            $categories[] = [
                'name' => $data['category_name'],
                'keywords' => $data['keywords'],
                'example_query' => "Do you have any {$data['category_name']} products?"
            ];
        }

        return response()->json([
            'categories' => $categories,
            'brands' => array_keys($this->brandMappings)
        ]);
    }
}
