<?php


namespace Modules\Admin\Actions\Task;


use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetAllAction
{
    public function handle(Request $request)
    {
        $query = Task::query();
        $user = Auth::user();

        if (!$user->hasPermissionTo('task.assign')) {
            $query->whereRaw("FIND_IN_SET(?, assignees) > 0", [$user->id]);
        }

        if ($search = $request->input('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }


        if ($request->input('from_date')) {
            //$query->whereRaw("format_date('deadline_at','%Y-%m-%d') >= '" . \Carbon\Carbon::parse($request->input('from_date'))->format('Y-m-d') . "'");
            $query->whereDate('deadline_at','>=', $request->input('from_date'));
        }

        if ($request->input('to_date')) {
            //$query->whereRaw("format_date('deadline_at','%Y-%m-%d') <= '" . \Carbon\Carbon::parse($request->input('to_date'))->format('Y-m-d') . "'");
            $query->whereDate('deadline_at','<=', $request->input('to_date'));
        }

        $filter = $request->input('filter');
        if (!empty($filter)) {
            $query->where('task_process', $filter['task_process']);
        }

        if ($request->input('deleted') !== null) {
            $query->where('deleted', $request->input('deleted'));
        } else {
            $query->where('deleted', 0);
        }

        $query->orderBy('id', 'DESC');
        return $query->get();
    }
}
