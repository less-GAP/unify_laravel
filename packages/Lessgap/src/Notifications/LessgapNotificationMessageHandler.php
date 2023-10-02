<?php


namespace Lessgap\Notifications;


use Lessgap\Models\NotificationMessage;

class LessgapNotificationMessageHandler
{

    public function handle($eventSetting, NotificationData $notificationData)
    {
        $title = \Blade::render($eventSetting['data']['notification_tittle'] ?? 'Title', $notificationData->getData());
        $message = \Blade::render($eventSetting['data']['notification_message'] ?? 'Message', $notificationData->getData());
        foreach ($notificationData->toUsers($eventSetting['data']['notification_to']) as $user) {
            NotificationMessage::create(
                [
                    'event_name' => $eventSetting->event_name,
                    'title' => $title,
                    'message' => $message,
                    'username' => $user['username'],
                    'by_user' => json_encode($notificationData->byUser()),
                    'status' => 'unread',
                    'data' => json_encode($notificationData->getData()),
                ]
            );
        }

    }

}
