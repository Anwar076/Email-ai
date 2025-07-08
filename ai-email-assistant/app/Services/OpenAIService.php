<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    private $client;
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    public function generateEmailReply($originalEmail, $context = '')
    {
        try {
            $prompt = $this->buildPrompt($originalEmail, $context);

            $response = $this->client->post('chat/completions', [
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a professional email assistant. Generate polite, professional, and helpful email replies. Keep responses concise but comprehensive. Use appropriate business email etiquette.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => 500,
                    'temperature' => 0.7,
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['choices'][0]['message']['content'])) {
                return trim($data['choices'][0]['message']['content']);
            }

            throw new \Exception('Invalid response from OpenAI API');

        } catch (\Exception $e) {
            Log::error('Error generating AI reply: ' . $e->getMessage());
            throw $e;
        }
    }

    private function buildPrompt($originalEmail, $context = '')
    {
        $prompt = "Please generate a professional email reply for the following email:\n\n";
        $prompt .= "From: " . $originalEmail['from'] . "\n";
        $prompt .= "Subject: " . $originalEmail['subject'] . "\n";
        $prompt .= "Content: " . strip_tags($originalEmail['body'] ?: $originalEmail['snippet']) . "\n\n";
        
        if (!empty($context)) {
            $prompt .= "Additional context: " . $context . "\n\n";
        }

        $prompt .= "Generate a professional and appropriate reply. ";
        $prompt .= "Include a proper greeting, address the main points from the original email, ";
        $prompt .= "provide helpful information or answers, and end with a professional closing. ";
        $prompt .= "Format the response as HTML for email.\n\n";
        $prompt .= "Reply:";

        return $prompt;
    }

    public function generateEmailSummary($email)
    {
        try {
            $prompt = "Please provide a brief summary of this email:\n\n";
            $prompt .= "From: " . $email['from'] . "\n";
            $prompt .= "Subject: " . $email['subject'] . "\n";
            $prompt .= "Content: " . strip_tags($email['body'] ?: $email['snippet']) . "\n\n";
            $prompt .= "Provide a concise 1-2 sentence summary of the main points.";

            $response = $this->client->post('chat/completions', [
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => 100,
                    'temperature' => 0.5,
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['choices'][0]['message']['content'])) {
                return trim($data['choices'][0]['message']['content']);
            }

            return 'Unable to generate summary';

        } catch (\Exception $e) {
            Log::error('Error generating email summary: ' . $e->getMessage());
            return 'Unable to generate summary';
        }
    }
}
