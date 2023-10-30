<?php


namespace Modules\Admin\Actions\Patient;


use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetAllAction
{
    public function handle(Request $request)
    {
        $query = Patient::query();
        if ($request->input('unify_process')) {
            $query->where('unify_process', $request->input('unify_process'));
        }
        return $query->get();
    }
}
