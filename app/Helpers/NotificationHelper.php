<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;

class NotificationHelper
{
    /**
     * Buat notifikasi untuk satu user.
     */
    public static function notify(
        string $userId,
        string $type,
        string $title,
        string $message,
        ?array $data = null,
        ?string $actionUrl = null,
    ): Notification {
        return Notification::create([
            'user_id'    => $userId,
            'type'       => $type,
            'title'      => $title,
            'message'    => $message,
            'data'       => $data,
            'action_url' => $actionUrl,
            'is_read'    => false,
        ]);
    }

    /**
     * Buat notifikasi untuk banyak user sekaligus.
     */
    public static function notifyMany(
        array $userIds,
        string $type,
        string $title,
        string $message,
        ?array $data = null,
        ?string $actionUrl = null,
    ): void {
        $notifications = [];
        $now = now();

        foreach ($userIds as $userId) {
            if (empty($userId)) continue;
            $notifications[] = [
                'id'         => (string) \Illuminate\Support\Str::orderedUuid(),
                'user_id'    => $userId,
                'type'       => $type,
                'title'      => $title,
                'message'    => $message,
                'data'       => $data ? json_encode($data) : null,
                'action_url' => $actionUrl,
                'is_read'    => false,
                'read_at'    => null,
                'created_at' => $now,
            ];
        }

        if (!empty($notifications)) {
            Notification::insert($notifications);
        }
    }

    /**
     * Notifikasi ke semua admin & atasan.
     */
    public static function notifyAdmins(
        string $type,
        string $title,
        string $message,
        ?array $data = null,
        ?string $actionUrl = null,
    ): void {
        $adminIds = User::whereIn('role', ['admin', 'atasan'])
            ->where('is_active', true)
            ->pluck('id')
            ->toArray();

        self::notifyMany($adminIds, $type, $title, $message, $data, $actionUrl);
    }
}
