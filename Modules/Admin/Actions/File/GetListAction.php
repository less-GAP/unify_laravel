<?php


namespace Modules\Admin\Actions\File;


use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetListAction
{
    public function handle(Request $request)
    {
        $query = File::query();
        $user = Auth::user();

        if(!$user->hasRole('Admin')){
            $query->where('created_by', $user->id);
        }

        if ($search = $request->input('search')) {
            $query->where('title', 'like', '%' . $search . '%');
        }
        
        $query->orderBy('id', $request->input('order', 'DESC'));

        // if ($request->input('deleted') !== null) {
        //     $query->where('deleted', $request->input('deleted'));
        // }else{
        //     $query->where('deleted', 0);
        // }

        return $query->paginate($request->input('perPage', 20));
    }
}
