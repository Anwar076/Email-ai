<?php

namespace App\Http\Controllers;

use App\Services\GmailService;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    private $gmailService;
    private $openAIService;

    public function __construct(GmailService $gmailService, OpenAIService $openAIService)
    {
        $this->gmailService = $gmailService;
        $this->openAIService = $openAIService;
        $this->middleware('auth');
    }

    /**
     * Generate AI reply for a specific email
     */
    public function generateReply($messageId)
    {
        try {
            $accessToken = Session::get('gmail_access_token');
            
            if (!$accessToken) {
                return response()->json([
                    'error' => 'Gmail not connected. Please connect your Gmail account first.'
                ], 401);
            }

            $this->gmailService->setAccessToken($accessToken);
            $email = $this->gmailService->getEmail($messageId);
            
            $aiReply = $this->openAIService->generateEmailReply($email);
            
            // Store the generated reply in session for potential editing
            Session::put("ai_reply_{$messageId}", $aiReply);
            
            return response()->json([
                'success' => true,
                'reply' => $aiReply,
                'original_email' => $email
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error generating AI reply: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to generate AI reply: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send AI generated reply
     */
    public function sendReply(Request $request, $messageId)
    {
        try {
            $accessToken = Session::get('gmail_access_token');
            
            if (!$accessToken) {
                return response()->json([
                    'error' => 'Gmail not connected. Please connect your Gmail account first.'
                ], 401);
            }

            $this->gmailService->setAccessToken($accessToken);
            
            // Get the original email
            $originalEmail = $this->gmailService->getEmail($messageId);
            
            // Get the reply content (either from request or session)
            $replyContent = $request->input('reply_content');
            if (!$replyContent) {
                $replyContent = Session::get("ai_reply_{$messageId}");
            }
            
            if (!$replyContent) {
                return response()->json([
                    'error' => 'No reply content found. Please generate a reply first.'
                ], 400);
            }

            // Send the reply
            $result = $this->gmailService->sendReply($messageId, $replyContent, $originalEmail);
            
            // Clear the stored reply
            Session::forget("ai_reply_{$messageId}");
            
            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully!',
                'gmail_response' => $result
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error sending reply: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to send reply: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get email details for preview
     */
    public function getEmailDetails($messageId)
    {
        try {
            $accessToken = Session::get('gmail_access_token');
            
            if (!$accessToken) {
                return response()->json([
                    'error' => 'Gmail not connected. Please connect your Gmail account first.'
                ], 401);
            }

            $this->gmailService->setAccessToken($accessToken);
            $email = $this->gmailService->getEmail($messageId);
            
            return response()->json([
                'success' => true,
                'email' => $email
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching email details: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to fetch email details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate email summary using AI
     */
    public function generateSummary($messageId)
    {
        try {
            $accessToken = Session::get('gmail_access_token');
            
            if (!$accessToken) {
                return response()->json([
                    'error' => 'Gmail not connected. Please connect your Gmail account first.'
                ], 401);
            }

            $this->gmailService->setAccessToken($accessToken);
            $email = $this->gmailService->getEmail($messageId);
            
            $summary = $this->openAIService->generateEmailSummary($email);
            
            return response()->json([
                'success' => true,
                'summary' => $summary
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error generating email summary: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to generate email summary: ' . $e->getMessage()
            ], 500);
        }
    }
}