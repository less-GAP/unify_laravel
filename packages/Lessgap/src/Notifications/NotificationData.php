<?php


namespace Lessgap\Notifications;


use App\Models\User;

class NotificationData
{
    protected $data;

    public function __construct(protected $model)
    {
        $this->data = $model->toArray();
    }

    public function getEventName()
    {
        return $this->eventName;
    }

    public function getData()
    {
        return $this->data;
    }

    public function byUser()
    {
        return [
            'full_name' => auth('admin')->user()->full_name??'',
            'id' => auth('admin')->user()->id??'',
            'profile_photo_url' => auth('admin')->user()->profile_photo_url??'',
        ];
    }

    public function toUsers($types)
    {
        $result = [];
        if (in_array('admin', $types)) {
            $result = array_merge($result, (array)$this->admins());
        }
        if (in_array('owner', $types)) {
            $result = array_merge($result,  (array)$this->owners());
        }
        return $result;
    }

    public function admins()
    {
        return User::whereHas('roles.permissions', function ($subquery) {
            return $subquery->where('permissions.name', 'Admin')->orWhere('permissions.name', '*');
        })->select(['full_name','username','id'])->get()->toArray();
    }

    public function owners()
    {
        return [$this->model->owner->only(['full_name','username','id'])];
    }
}
