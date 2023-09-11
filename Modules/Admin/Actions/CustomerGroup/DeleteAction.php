<?php


namespace Modules\Admin\Actions\CustomerGroup;


use App\Models\CustomerGroup;
use Illuminate\Http\Request;

class DeleteAction
{
    public function handle(Request $request)
    {
        try {
            $query = CustomerGroup::query();
            $del = $query->find($request->route('id'))->delete();
            $output = [
                'code' => 1,
                'message' => 'Update success !',
                'data' => $del
            ];
        } catch (\Throwable $e) {
            $output = [
                'code' => 0,
                'message' => 'Update failed ! ' . $e->getMessage(),
                'data' => $request->route('id')
            ];
        }
        return $output;
    }
}
