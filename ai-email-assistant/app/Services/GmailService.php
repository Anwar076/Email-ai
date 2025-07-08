<?php

namespace App\Services;

use Google\Client;
use Google\Service\Gmail;
use Illuminate\Support\Facades\Log;

class GmailService
{
    private $client;
    private $gmail;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect'));
        $this->client->addScope(Gmail::GMAIL_READONLY);
        $this->client->addScope(Gmail::GMAIL_SEND);
        $this->client->addScope(Gmail::GMAIL_COMPOSE);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
    }

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function authenticate($code)
    {
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($code);
        
        if (array_key_exists('error', $accessToken)) {
            throw new \Exception('Error fetching access token: ' . $accessToken['error']);
        }

        return $accessToken;
    }

    public function setAccessToken($accessToken)
    {
        $this->client->setAccessToken($accessToken);
        
        if ($this->client->isAccessTokenExpired()) {
            if ($this->client->getRefreshToken()) {
                $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
            } else {
                throw new \Exception('Access token expired and no refresh token available');
            }
        }

        $this->gmail = new Gmail($this->client);
    }

    public function getEmails($maxResults = 10)
    {
        try {
            $results = $this->gmail->users_messages->listUsersMessages('me', [
                'maxResults' => $maxResults,
                'q' => 'in:inbox'
            ]);

            $messages = $results->getMessages();
            $emails = [];

            if (!$messages) {
                return $emails;
            }

            foreach ($messages as $message) {
                $msg = $this->gmail->users_messages->get('me', $message->getId(), [
                    'format' => 'full'
                ]);

                $headers = $msg->getPayload()->getHeaders();
                $subject = '';
                $from = '';
                $date = '';

                foreach ($headers as $header) {
                    switch ($header->getName()) {
                        case 'Subject':
                            $subject = $header->getValue();
                            break;
                        case 'From':
                            $from = $header->getValue();
                            break;
                        case 'Date':
                            $date = $header->getValue();
                            break;
                    }
                }

                $snippet = $msg->getSnippet();
                $body = $this->getMessageBody($msg->getPayload());

                $emails[] = [
                    'id' => $message->getId(),
                    'subject' => $subject,
                    'from' => $from,
                    'date' => $date,
                    'snippet' => $snippet,
                    'body' => $body
                ];
            }

            return $emails;
        } catch (\Exception $e) {
            Log::error('Error fetching emails: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getEmail($messageId)
    {
        try {
            $msg = $this->gmail->users_messages->get('me', $messageId, [
                'format' => 'full'
            ]);

            $headers = $msg->getPayload()->getHeaders();
            $subject = '';
            $from = '';
            $date = '';
            $to = '';

            foreach ($headers as $header) {
                switch ($header->getName()) {
                    case 'Subject':
                        $subject = $header->getValue();
                        break;
                    case 'From':
                        $from = $header->getValue();
                        break;
                    case 'To':
                        $to = $header->getValue();
                        break;
                    case 'Date':
                        $date = $header->getValue();
                        break;
                }
            }

            $body = $this->getMessageBody($msg->getPayload());

            return [
                'id' => $messageId,
                'subject' => $subject,
                'from' => $from,
                'to' => $to,
                'date' => $date,
                'body' => $body,
                'snippet' => $msg->getSnippet()
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching email: ' . $e->getMessage());
            throw $e;
        }
    }

    public function sendReply($messageId, $replyContent, $originalEmail)
    {
        try {
            $replySubject = 'Re: ' . preg_replace('/^Re: /', '', $originalEmail['subject']);
            
            $message = new \Google\Service\Gmail\Message();
            
            $rawMessage = "To: {$originalEmail['from']}\r\n";
            $rawMessage .= "Subject: {$replySubject}\r\n";
            $rawMessage .= "In-Reply-To: {$messageId}\r\n";
            $rawMessage .= "References: {$messageId}\r\n";
            $rawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
            $rawMessage .= "\r\n";
            $rawMessage .= $replyContent;

            $message->setRaw(base64url_encode($rawMessage));

            $result = $this->gmail->users_messages->send('me', $message);
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Error sending reply: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getMessageBody($payload)
    {
        $body = '';
        
        if ($payload->getParts()) {
            foreach ($payload->getParts() as $part) {
                if ($part->getMimeType() == 'text/plain' || $part->getMimeType() == 'text/html') {
                    $data = $part->getBody()->getData();
                    $body .= base64url_decode($data);
                } elseif ($part->getParts()) {
                    $body .= $this->getMessageBody($part);
                }
            }
        } else {
            $data = $payload->getBody()->getData();
            if ($data) {
                $body = base64url_decode($data);
            }
        }
        
        return $body;
    }
}

function base64url_encode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data)
{
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}
