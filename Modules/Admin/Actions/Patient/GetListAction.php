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
        
        if($user->hasRole('Admin') === false){
            $query->where('sale_user', $user->id);
            
            if($user->hasRole('Seller')){
                $query->where('unify_process', '=', '0');
            }
        }

        if ($search = $request->input('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->paginate($request->input('perPage', 15));
    }
}
