<?php
namespace App\Http\Controllers;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
class OpenAIController extends Controller
{
    protected OpenAIService $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function generate(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:255',
        ]);

        try {
            $text = $this->openAIService->generateText($request->prompt);

            return response()->json([
                'reply' => trim($text), // Return trimmed response
            ]);
        } catch (\Exception $e) {
            \Log::error('Error generating text', ['error' => $e->getMessage()]);
            return response()->json([
                'reply' => 'Sorry, something went wrong. Please try again later.',
            ], 500);
        }
    }
}
