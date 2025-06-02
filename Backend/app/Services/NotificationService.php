<?php

namespace App\Services;

use App\Models\User;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\Log;

use Kreait\Firebase\Messaging as FirebaseMessaging;

class NotificationService
{
    public function sendNotification($userId, $title, $message)
    {
        //   $messaging = app('firebase.messaging');
        //     $notification = Notification::create($title, $message);

        //     $cloudMessage = CloudMessage::withTarget('token', 'fdKY8hbdREOmq3R8h7MBxS:APA91bHNrhT7sD3k7Ihb4rgkNeZcuFa09Rt1sLto_pkhB08jkhUtyyBYqwqQq3uIBoozQ24xnossAdMa91JwY8iHuR1ec0YBWf3XX23hrKoBRixqCCKVrhM')
        //         ->withNotification($notification);;

        //     try {
        //         $messaging->send($cloudMessage);
        //     }catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
        //         Log::info("Removed invalid FCM token for user ");
        //     } catch (\Exception $e) {
        //         Log::error('Failed to send notification', ['error' => $e->getMessage()]);
        //     }
        $user = User::find($userId);
        if ($user && $user->fcm_token) {
            $token = $user->fcm_token;
            // Kirim notifikasi ke perangkat Android
            $messaging = app('firebase.messaging');
            $notification = Notification::create($title, $message);

            $cloudMessage = CloudMessage::withTarget('token', 'fdKY8hbdREOmq3R8h7MBxS:APA91bHNrhT7sD3k7Ihb4rgkNeZcuFa09Rt1sLto_pkhB08jkhUtyyBYqwqQq3uIBoozQ24xnossAdMa91JwY8iHuR1ec0YBWf3XX23hrKoBRixqCCKVrhM')
                ->withNotification($notification);;

            try {
                $messaging->send($cloudMessage);
            }catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
                Log::info("Removed invalid FCM token for user {$user->id}");
            } catch (\Exception $e) {
                Log::error('Failed to send notification', ['error' => $e->getMessage()]);
            }
        }
    }
}
