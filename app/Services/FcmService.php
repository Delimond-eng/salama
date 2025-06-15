<?php 
namespace App\Services;

use Google\Client;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Log;

class FcmService
{
    protected $http;
    protected $accessToken;
    protected $projectId;

    public function __construct()
    {
        $this->http = new HttpClient();

        $client = new Client();
        $client->useApplicationDefaultCredentials();
        $client->setAuthConfig(storage_path('app/firebase/credentials.json'));
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $this->accessToken = $client->fetchAccessTokenWithAssertion()['access_token'];
        Log::info($this->accessToken);
        $this->projectId = json_decode(file_get_contents(storage_path('app/firebase/credentials.json')), true)['project_id'];
    }

    public function sendNotification($deviceToken, $title, $body)
    {
        $message = [
            "message" => [
                "token" => $deviceToken,
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                ],
                "android" => [
                    "priority" => "high"
                ],
                "apns" => [
                    "headers" => [
                        "apns-priority" => "10"
                    ]
                ]
            ]
        ];

        $response = $this->http->post("https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send", [
            'headers' => [
                'Authorization' => "Bearer {$this->accessToken}",
                'Content-Type' => 'application/json',
            ],
            'json' => $message,
        ]);

        return json_decode($response->getBody(), true);
    }
}
