<?php


namespace Modules\Admin\Actions\Patient;


use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetDetailAction
{
    public function handle(Request $request)
    {
        $patient = [];
        $user = Auth::user();
        $result_fail = [
            'status' => 404,
            'message' => 'Patient not found!',
            'data' => $patient
        ];
        $id = $request->route('id');
        if (!$id) return $result_fail;

        $patient = Patient::with('tasks')->where('id', $id)->first();

        if (!$patient) return $result_fail;

        $result_trash_admin = [
            'status' => 200,
            'message' => 'Patient is trashed!',
            'data' => $patient
        ];
        $result_trash_not_admin = [
            'status' => 404,
            'message' => 'Patient is trashed!',
            'data' => [],
        ];

        if ($patient->unify_deleted == 1){
            if($user->hasRole('Admin')){
                return $result_trash_admin;
            } else {
                return $result_trash_not_admin;
            }
        }

        $tasks = $patient->tasks;
        $assignees = [];
        foreach ($tasks as $task) {
            if ($task->deleted == 0 && $task->is_completed == 0) {
                $assignees = array_merge($assignees, $task->assignees);
            }
        }
        $result_success = [
            'status' => 200,
            'message' => 'Success!',
            'data' => $patient
        ];
        if ($user->hasRole('Admin')){
            return $result_success;
        } else if ($user->hasPermissionTo('patient.view')) {
            if (
                $user->hasRole('Seller') && $patient->sale_user == $user->id && $patient->unify_process == 0 ||
                $user->hasRole('Seller') && in_array($user->id, array_column($assignees, 'id'))  ||
                $user->hasRole('Seller Manager') && $patient->unify_process == 0
            ) {
                return $result_success;
            }
        }

        $result_fail = [
            'status' => 403,
            'message' => 'Not permission to access this patient!',
            'data' => null
        ];
        return $result_fail;
    }
}
