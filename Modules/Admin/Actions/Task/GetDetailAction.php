<?php


namespace Modules\Admin\Actions\Task;


use App\Models\Task;
use App\Providers\AbstractModuleProvider;
use Illuminate\Http\Request;

class GetDetailAction
{
    public function handle(Request $request)
    {
        $task = [];
        $id = $request->route('id');
        if($id > 0){
            $task = Task::where('id',$id)->first();
            return [
                'code' => 1,
                'message' => 'Success!',
                'data' => $task->load('patients'),
            ];
        }
        return [
            'code' => 0,
            'message' => 'Error!',
            'data' => $task
        ];
    }
}
