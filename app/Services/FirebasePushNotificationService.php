<?php

namespace App\Services;

use App\Models\PushDevice;
use App\Models\User;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class FirebasePushNotificationService
{
    private ?string $accessToken = null;

    /**
     * @param array<string, string> $data
     * @return array{sent: int, failed: int}
     */
    public function sendToUser(User $user, string $title, string $body, array $data = []): array
    {
        $devices = $user->pushDevices()->get();

        if ($devices->isEmpty()) {
            return ['sent' => 0, 'failed' => 0];
        }

        $projectId = config('services.firebase.project_id');
        if (!is_string($projectId) || $projectId === '') {
            throw new RuntimeException('FIREBASE_PROJECT_ID is not configured.');
        }

        $accessToken = $this->accessToken($projectId);
        $sent = 0;
        $failed = 0;

        foreach ($devices as $device) {
            try {
                $response = Http::acceptJson()
                    ->withToken($accessToken)
                    ->timeout(10)
                    ->post(
                        "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
                        [
                            'message' => [
                                'token' => $device->token,
                                'notification' => [
                                    'title' => $title,
                                    'body' => $body,
                                ],
                                'data' => $data,
                                'android' => [
                                    'priority' => 'HIGH',
                                    'notification' => [
                                        'channel_id' => 'appointments',
                                    ],
                                ],
                            ],
                        ]
                    );

                if ($response->successful()) {
                    $sent++;

                    continue;
                }

                if ($this->isUnregisteredToken($response->json())) {
                    $device->delete();
                }

                Log::error('Firebase push notification was rejected.', [
                    'user_id' => $user->id,
                    'push_device_id' => $device->id,
                    'http_status' => $response->status(),
                ]);
                $failed++;
            } catch (Throwable $exception) {
                report($exception);
                Log::error('Firebase push notification could not be delivered.', [
                    'user_id' => $user->id,
                    'push_device_id' => $device->id,
                ]);
                $failed++;
            }
        }

        return ['sent' => $sent, 'failed' => $failed];
    }

    private function accessToken(string $projectId): string
    {
        if ($this->accessToken !== null) {
            return $this->accessToken;
        }

        $credentialsJson = config('services.firebase.service_account_json');
        if (!is_string($credentialsJson) || $credentialsJson === '') {
            throw new RuntimeException('FIREBASE_SERVICE_ACCOUNT_JSON is not configured.');
        }

        $credentials = json_decode($credentialsJson, true);
        if (!is_array($credentials) || empty($credentials['client_email']) || empty($credentials['private_key'])) {
            throw new RuntimeException('FIREBASE_SERVICE_ACCOUNT_JSON must contain a valid service account.');
        }
        if (($credentials['project_id'] ?? null) !== $projectId) {
            throw new RuntimeException('FIREBASE_PROJECT_ID must match the service account project_id.');
        }

        $serviceAccount = new ServiceAccountCredentials(
            ['https://www.googleapis.com/auth/firebase.messaging'],
            $credentials
        );
        $token = $serviceAccount->fetchAuthToken();

        if (empty($token['access_token'])) {
            throw new RuntimeException('Google OAuth did not return a Firebase access token.');
        }

        $this->accessToken = $token['access_token'];

        return $this->accessToken;
    }

    /**
     * @param array<string, mixed>|null $response
     */
    private function isUnregisteredToken(?array $response): bool
    {
        foreach ($response['error']['details'] ?? [] as $detail) {
            if (($detail['errorCode'] ?? null) === 'UNREGISTERED') {
                return true;
            }
        }

        return false;
    }
}
