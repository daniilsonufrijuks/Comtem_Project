<?php
namespace App\Services;
use OpenAI;
use OpenAI\Client;

class OpenAIService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = OpenAI::client(config('services.openai.api_key')); // Correct instantiation
    }

    public function generateText(string $prompt): string
    {
        try {
            $response = $this->client->completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => $prompt,
                'max_tokens' => 150,
                'curl_options' => [
                    CURLOPT_SSL_VERIFYPEER => false,  // Disables SSL verification
                ],
            ]);

            return $response['choices'][0]['text'];
        } catch (\Exception $e) {
            \Log::error('OpenAI API Error: ' . $e->getMessage());
            throw new \RuntimeException('Failed to generate text from OpenAI.');
        }
    }
}
