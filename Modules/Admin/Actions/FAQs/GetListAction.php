<?php


namespace Modules\Admin\Actions\FAQs;


use App\Models\FAQs;
use Illuminate\Http\Request;

class GetListAction
{
    public function handle(Request $request)
    {

        $query = FAQs::query();
        if ($search = $request->input('filter')) {
            $query->where('name', 'like', '%'.$search['search'].'%');
        }

        if ($by = $request->input('orderby') ?? 'id') {
            $query->orderBy($by, 'DESC');
        }
        return $query->paginate($request->input('perPage',20));
    }
}
