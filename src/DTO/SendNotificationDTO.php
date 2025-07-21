<?php

namespace App\DTO;

class SendNotificationDTO
{
    public string $dsn;
    public int $fromUserId;
    public int $toUserId;
    public string $icon;
    public string $notificationTitle;
    public string $notificationMessage;

    public function __construct(
        string $dsn,
        int $fromUserId,
        int $toUserId,
        string $icon,
        string $notificationTitle,
        string $notificationMessage,
    ) {
        $this->dsn = $dsn;
        $this->fromUserId = $fromUserId;
        $this->toUserId = $toUserId;
        $this->icon = $icon;
        $this->notificationTitle = $notificationTitle;
        $this->notificationMessage = $notificationMessage;
    }

    public function toArray(): array
    {
        return [
            'from_user_id' => $this->fromUserId,
            'to_user_id' => $this->toUserId,
            'icon' => $this->icon,
            'title' => $this->notificationTitle,
            'message' => $this->notificationMessage,
        ];
    }
}
