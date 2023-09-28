<?php

use App\Services\EmailService;

app(EmailService::class)->add(['email_to' => 'phuong@lessgap.com','data' => ['hello' => "hello Phuong"]], 1,true);
