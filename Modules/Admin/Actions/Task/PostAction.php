<?php


namespace Modules\Admin\Actions\Task;

use App\Models\Task;
use Illuminate\Http\Request;
use Modules\Admin\Middleware\AdminIsAuthenticated;

class PostAction
{
    public function handle(Request $request)
    {
        $data = $request->all();
        // if(auth()->user()->role != 'admin'){
        //     $data['saler_id'] = auth()->user()->id;
        // }

        try {
            $task = new Task();
            if (isset($data['id']) && $data['id'] > 0) {
                $task = Task::find($data['id']);
            }

            $task->fill($data);
            $task->save();

            $output = [
                'code' => 1,
                'message' => 'Success!',
                'data' => $task
            ];
        } catch (\Throwable $e) {
            $output = [
                'code' => 0,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
        return $output;
    }
}
