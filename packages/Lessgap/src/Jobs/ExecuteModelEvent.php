<?php

namespace Lessgap\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Lessgap\Handlers\NotificationEventHandler;

class ExecuteModelEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $event, public $model)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        app(NotificationEventHandler::class)->handle($this->event, $this->model->notificationData());
    }
}
