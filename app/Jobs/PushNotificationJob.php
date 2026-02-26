<?php

namespace App\Jobs;

use App\Models\PushSubscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userIds;
    public $payload;

    /**
     * Create a new job instance.
     */
    public function __construct(array $userIds, array $payload)
    {
        $this->userIds = $userIds;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $auth = [
            'VAPID' => [
                'subject' => env('APP_URL', 'mailto:admin@example.com'),
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ];

        // If keys aren't set, silently exit or log error depending on your preference
        if (!env('VAPID_PUBLIC_KEY') || !env('VAPID_PRIVATE_KEY')) {
            Log::warning('VAPID keys not set. Push notifications will not be sent.');
            return;
        }

        $webPush = new WebPush($auth);
        
        $subscriptions = PushSubscription::whereIn('user_id', $this->userIds)->get();

        foreach ($subscriptions as $subModel) {
            $subscription = Subscription::create([
                'endpoint' => $subModel->endpoint,
                'keys' => [
                    'p256dh' => $subModel->public_key,
                    'auth' => $subModel->auth_token,
                ],
            ]);

            $webPush->queueNotification(
                $subscription,
                json_encode($this->payload)
            );
        }

        // Flush the queue to send all notifications
        foreach ($webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();

            if ($report->isSuccess()) {
                Log::info("[WebPush] Message sent successfully to {$endpoint}.");
            } else {
                Log::error("[WebPush] Message failed to sent to {$endpoint}: {$report->getReason()}");

                // If the subscription is expired or unsubscribed, remove it from DB
                if ($report->isSubscriptionExpired()) {
                    PushSubscription::where('endpoint', $endpoint)->delete();
                    Log::info("[WebPush] Deleted expired subscription: {$endpoint}");
                }
            }
        }
    }
}
