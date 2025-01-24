<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use MalteKuhr\LaravelGPT\Facades\GPT;
class ChatController extends Controller
{
    public function chat(Request $request)
    {
//        try {
//            // Validate input
//            $request->validate([
//                'message' => 'required|string|max:500',
//            ]);
//
//            // Make OpenAI API call
//            $client = new Client();
//            $response = $client->post('https://api.openai.com/v1/chat/completions', [
//                'headers' => [
//                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
//                    'Content-Type' => 'application/json',
//                ],
//                'json' => [
//                    'model' => 'gpt-3.5-turbo',
//                    'messages' => [
//                        ['role' => 'system', 'content' => 'You are a helpful assistant.'],
//                        ['role' => 'user', 'content' => $request->input('message')],
//                    ],
//                    'max_tokens' => 150,
//                    'temperature' => 0.7,
//                ],
//            ]);
//
//            $result = json_decode($response->getBody()->getContents(), true);
//
//            // Check if AI response exists
//            if (!isset($result['choices'][0]['message']['content'])) {
//                return response()->json([
//                    'reply' => "Sorry, I couldn't process your request. Please try again.",
//                ], 500);
//            }
//
//            // Return AI response
//            return response()->json([
//                'reply' => $result['choices'][0]['message']['content'],
//            ]);
//        } catch (\Exception $e) {
//            // Log the error and return a generic response
//            \Log::error('ChatController error: ' . $e->getMessage());
//            return response()->json([
//                'reply' => 'An error occurred. Please try again later.',
//            ], 500);
//        }
        $message = $request->input('message');
        $apiKey = env('HUGGINGFACE_API_KEY');
        $modelEndpoint = 'https://api-inference.huggingface.co/models/gpt2';

        try {
            $client = new Client();
            $response = $client->post($modelEndpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'inputs' => $message,
                ],
//                'verify' => false,
            ]);

            $responseBody = $response->getBody()->getContents();
            \Log::info('Hugging Face API Response: ' . $responseBody);

            $data = json_decode($response->getBody()->getContents(), true);

            if (!isset($data[0]['generated_text'])) {
                return response()->json([
                    'reply' => "The AI model couldn't generate a response. Please try again.",
                ], 500);
            }

            $generatedText = $data[0]['generated_text'] ?? "I'm unable to process your request right now.";
            return response()->json([
                'reply' => $generatedText,
            ]);
        } catch (\Exception $e) {
            \Log::error('ChatController error: ' . $e->getMessage());
            return response()->json([
                'reply' => 'An error occurred while processing your request. Please try again later.',
            ], 500);
        }
    }
}
