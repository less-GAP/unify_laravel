<?php


namespace Modules\Admin\Actions\Task;


use App\Models\Task;
use App\Providers\AbstractModuleProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class GetDetailAction
{
    public function handle(Request $request)
    {
        $user = Auth::user();
        $task = [];
        $id = $request->route('id');

        if($id > 0){
            $task = Task::where('id',$id)->first();
            return $task;
        }

        
        return $task;
    }
}
