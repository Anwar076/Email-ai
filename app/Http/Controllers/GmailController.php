<?php

namespace App\Http\Controllers;

use App\Services\GmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class GmailController extends Controller
{
    private $gmailService;

    public function __construct(GmailService $gmailService)
    {
        $this->gmailService = $gmailService;
        $this->middleware('auth');
    }

    /**
     * Redirect to Google OAuth2 authorization
     */
    public function redirectToGoogle()
    {
        try {
            $authUrl = $this->gmailService->getAuthUrl();
            return redirect($authUrl);
        } catch (\Exception $e) {
            Log::error('Error redirecting to Google: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Failed to connect to Gmail. Please check your configuration.');
        }
    }

    /**
     * Handle Google OAuth2 callback
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $code = $request->get('code');
            
            if (!$code) {
                return redirect()->route('dashboard')->with('error', 'Authorization was denied or failed.');
            }

            $accessToken = $this->gmailService->authenticate($code);
            
            // Store the access token in session
            Session::put('gmail_access_token', $accessToken);
            
            return redirect()->route('inbox')->with('success', 'Gmail connected successfully!');
            
        } catch (\Exception $e) {
            Log::error('Error handling Google callback: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Failed to authenticate with Gmail: ' . $e->getMessage());
        }
    }

    /**
     * Display inbox with emails
     */
    public function inbox()
    {
        try {
            $accessToken = Session::get('gmail_access_token');
            
            if (!$accessToken) {
                return redirect()->route('dashboard')->with('error', 'Please connect your Gmail account first.');
            }

            $this->gmailService->setAccessToken($accessToken);
            $emails = $this->gmailService->getEmails(10);
            
            return view('inbox', compact('emails'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching emails: ' . $e->getMessage());
            
            // If token expired, clear it and redirect to connect again
            if (strpos($e->getMessage(), 'token') !== false || strpos($e->getMessage(), 'expired') !== false) {
                Session::forget('gmail_access_token');
                return redirect()->route('dashboard')->with('error', 'Gmail session expired. Please reconnect your account.');
            }
            
            return redirect()->route('dashboard')->with('error', 'Failed to fetch emails: ' . $e->getMessage());
        }
    }

    /**
     * Check Gmail connection status
     */
    public function checkConnection()
    {
        $accessToken = Session::get('gmail_access_token');
        
        return response()->json([
            'connected' => !empty($accessToken),
            'token_exists' => !empty($accessToken)
        ]);
    }

    /**
     * Disconnect Gmail account
     */
    public function disconnect()
    {
        Session::forget('gmail_access_token');
        return redirect()->route('dashboard')->with('success', 'Gmail account disconnected successfully.');
    }
}