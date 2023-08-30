<?php


namespace Modules\Admin\Actions\Patient;


use App\Models\Patient;
use Illuminate\Http\Request;

class DeletePatientAction
{
    public function handle(Request $request)
    {
        try {
            $query = Patient::query();
            $del = $query->find($request->route('id'))->delete();
            $output = [
                'code' => 1,
                'message' => 'Thành công !',
                'data' => $del
            ];
        } catch (\Throwable $e) {
            $output = [
                'code' => 0,
                'message' => 'Thất bại ! ' . $e->getMessage(),
                'data' => $request->route('id')
            ];
        }

        return $output;
    }
}
