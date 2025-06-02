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
        $user = User::find($userId);

        if ($user && $user->fcm_token) {
            $token = $user->fcm_token;

            $messaging = app('firebase.messaging');
            $notification = Notification::create($title, $message);

            $cloudMessage = CloudMessage::withTarget('token', $token)
                ->withNotification($notification);

            try {
                $messaging->send($cloudMessage);
                Log::info("Notifikasi berhasil dikirim ke user ID {$userId}");
            } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
                Log::warning("FCM token tidak valid untuk user ID {$userId}");
            } catch (\Exception $e) {
                Log::error('Gagal mengirim notifikasi', ['error' => $e->getMessage()]);
            }
        } else {
            Log::warning("User ID {$userId} tidak ditemukan atau tidak memiliki FCM token.");
        }
    }
}
