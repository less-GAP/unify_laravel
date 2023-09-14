<?php


namespace Modules\Admin\Actions\Task;


use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetListAction
{
    public function handle(Request $request)
    {
        $query = Task::query();
        $user = Auth::user();

        if(!$user->hasPermissionTo('task.assign')){
            $query->whereRaw("FIND_IN_SET(?, assignees) > 0", [$user->id]);
        }

        if ($search = $request->input('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        if ($request->input('patient_id') !== null) {
            $query->where('patient_id', $request->input('patient_id'));
        }
        if ($request->input('is_completed') !== null) {
            $query->where('is_completed', $request->input('is_completed'));
        }

        if ($request->input('deleted') !== null) {
            $query->where('deleted', $request->input('deleted'));
        }else{
            $query->where('deleted', 0);
        }

        if ($request->input('sort') !== null) {
            switch ($request->input('sort')) {
                case '-id':
                    $query->orderBy('id', $request->input('order', 'DESC'));
                    break;
                case 'id':
                    $query->orderBy('id', $request->input('order', 'ASC'));
                    break;
                case 'name':
                    $query->orderBy('name', $request->input('order', 'ASC'));
                    break;
                case '-updated_at':
                    $query->orderBy('updated_at', $request->input('order', 'DESC'));
                    break;
                default:
                    $query->orderBy('id', $request->input('order', 'DESC'));
                    break;
            }
        }else{
            $query->orderBy('id', $request->input('order', 'DESC'));
        }

        return $query->paginate($request->input('perPage', 20));
    }
}
