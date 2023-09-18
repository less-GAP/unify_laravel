<?php


namespace Modules\Admin\Actions\Doctor;


use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetListAction
{
    public function handle(Request $request)
    {
        $query = Doctor::query();
        $filter = $request->filter;

        if($filter){
            if ($filter['search']) {
                $query->where('full_name', 'like', '%' . $filter['search'] . '%');
            }
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

        return $query->paginate($request->input('perPage', 20));
    }
}
