<?php


namespace Modules\Admin\Actions\FAQs;


use App\Models\FAQs;
use Illuminate\Http\Request;

class DeleteAction
{
    public function handle(Request $request)
    {
        try {
            $query = FAQs::query();
            $del = $query->find($request->route('id'))->delete();
            $output = [
                'code' => 1,
                'message' => 'Successfull !',
                'data' => $del
            ];
        } catch (\Throwable $e) {
            $output = [
                'code' => 0,
                'message' => 'Failed ! ' . $e->getMessage(),
                'data' => $request->route('id')
            ];
        }

        return $output;
    }
}
