<?php


namespace Modules\Admin\Actions\Notification;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Lessgap\Models\NotificationMessage;

class ReadAction
{
    public function handle(Request $request)
    {
        $user = auth('admin')->user();
        return $user->notifications()->find($request->input('id'))->update(['read_at' => Carbon::now()]);
    }
}
