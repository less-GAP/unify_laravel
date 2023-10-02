<?php


namespace Modules\Admin\Actions\Notification;


use Illuminate\Http\Request;
use Lessgap\Models\NotificationMessage;

class ReadAction
{
    public function handle(Request $request)
    {
        return \DB::connection('realtime')->statement('UPDATE ' . $request->input('id') . ' SET status = \'read\'');
    }
}
