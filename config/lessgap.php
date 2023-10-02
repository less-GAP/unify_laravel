<?php
return [
    'notification_handler' => [
        'notification_message'=>\Lessgap\Notifications\LessgapNotificationMessageHandler::class,
        'notification_email'=>\Lessgap\Notifications\LessgapNotificationEmailHandler::class,
        'notification_desktop'=>\Lessgap\Notifications\LessgapNotificationDesktopHandler::class
    ],
    'notification' => [
        'model' => 'notifications'
    ]
];
