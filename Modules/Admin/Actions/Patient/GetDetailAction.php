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

        $tasks = $patient->tasks;
        $assignees = [];
        foreach ($tasks as $task) {
            if ($task->deleted == 0 && $task->is_completed == 0) {
                $assignees = array_merge($assignees, json_decode($task->assignees));
            }
        }
        if ($user->hasRole('Admin') || $user->hasPermissionTo('patient.view')) {
            if (
                $user->hasRole('Seller') && $patient->sale_user == $user->id && $patient->unify_process == 0 ||
                $user->hasRole('Seller') && in_array($user->id, $assignees)  ||
                $user->hasRole('Seller Manager') && $patient->unify_process == 0
            ) {
                $result_success = [
                    'status' => 200,
                    'message' => 'Success!',
                    'data' => $patient
                ];
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
