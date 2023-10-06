<?php


namespace Modules\Admin\Actions\Notification;


use Illuminate\Http\Request;
use Lessgap\Models\NotificationMessage;

class GetNotifications
{
    public function handle(Request $request)
    {
        $user = auth('admin')->user();
        return [
            'items'=>$user->notifications()->orderByDesc('id')->limit($request->input('perPage', 15))->get(),
            'unread_count'=>$user->notifications()->whereNull('read_at')->count()
        ];
    }
}
