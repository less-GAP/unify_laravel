<?php


namespace App\Notifications;

use Lessgap\Notifications\NotificationData;

class NotificationPatientData extends NotificationData
{
    public function getData()
    {
        return [
            'patient_name' => $this->model->full_name,
            'id' => $this->model->id
        ];
    }


}
