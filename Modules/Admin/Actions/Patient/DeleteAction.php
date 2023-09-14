<?php


namespace Modules\Admin\Actions\Patient;


use App\Models\Patient;
use Illuminate\Http\Request;

class DeleteAction
{
    public function handle(Request $request)
    {
        try {
            $query = Patient::query();
            $del = $query->find($request->route('id'))->update([
                'unify_deleted' => 1,
                'unify_deleted_at' => now(),
                'unify_deleted_by' => auth()->user()->id
            ]);
            $output = [
                'code' => 1,
                'message' => 'Success!',
                'data' => $del
            ];
        } catch (\Throwable $e) {
            $output = [
                'code' => 0,
                'message' => 'Fail! ' . $e->getMessage(),
                'data' => $request->route('id')
            ];
        }

        return $output;
    }
}
