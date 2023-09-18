<?php


namespace Modules\Admin\Actions\Patient;


use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetListAction
{
    public function handle(Request $request)
    {
        $query = Patient::query();
        $user = Auth::user();
        $filter = $request->filter;

        $query->with(['tasks' => function ($query_task) {
            $query_task->orderBy('created_at', 'ASC');
        }]);
        
        if(!$user->hasRole('Admin')){
            $query->where('sale_user', $user->id);
            
            if($user->hasRole('Seller')){
                $query->where('unify_process', '=', '0');
            }
        }

        if($filter){
            if (isset($filter['search'])) {
                $query->where('full_name', 'like', '%' . $filter['search'] . '%')
                ->orWhere('unify_number', 'like', '%' . $filter['search'] . '%')
                ->orWhere('phone', 'like', '%' . $filter['search'] . '%');
            }
            if (isset($filter['doctor_id'])) {
                $query->where('doctor_id', $filter['doctor_id']);
            }
            if (isset($filter['sale_user'])) {
                $query->where('sale_user', $filter['sale_user']);
            }
        }

        if ($request->input('deleted') !== null) {
            $query->where('unify_deleted', $request->input('deleted'));
        }else{
            $query->where('unify_deleted', 0);
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
                    $query->orderBy('full_name', $request->input('order', 'ASC'));
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

        return $query->paginate($request->input('perPage', 15));
    }
}
