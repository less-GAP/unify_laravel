<?php


namespace Modules\Admin\Actions\CustomerGroup;


use App\Models\CustomerGroup;
use Illuminate\Http\Request;

class GetListAction
{
    public function handle(Request $request)
    {
        $query = CustomerGroup::query();
        if ($search = $request->input('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        return $query->paginate($request->input('perPage', 15));
    }
}
