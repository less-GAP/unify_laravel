<?php


namespace Modules\Admin\Actions\Patient;


use App\Models\Patient;
use Illuminate\Http\Request;

class GetPatientListAction
{
    public function handle(Request $request)
    {
        $query = Patient::query();
        if ($search = $request->input('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->with('images')->paginate($request->input('perPage', 15));
    }
}
