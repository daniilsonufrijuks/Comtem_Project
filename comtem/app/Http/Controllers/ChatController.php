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
        // Validate the incoming request
        $client = new Client();

        try {
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                        ['role' => 'user', 'content' => $request->input('message')],
                    ],
                    'max_tokens' => 150,
                    'temperature' => 0.7,
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            // Log the response to see if it is valid
            Log::info('AI Response:', $result);

            return response()->json([
                'reply' => $result['choices'][0]['message']['content'],
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating response from OpenAI API:', ['error' => $e->getMessage()]);

            return response()->json([
                'error' => 'An error occurred while generating a response.',
            ], 500);
        }
    }
}
