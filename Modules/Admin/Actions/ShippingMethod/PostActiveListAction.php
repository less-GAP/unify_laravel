<?php


namespace Modules\Admin\Actions\ShippingMethod;

use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class PostActiveListAction
{
    public function handle(Request $request)
    {
        $data = $request->all();
        try {
            $res = ShippingMethod::whereIn('id', $data['items'])->update([
                'status' => $data['status']
            ]);

            $output = [
                'code' => 1,
                'message' => 'Success!',
                'data' => []
            ];
        } catch (\Throwable $e) {
            $output = [
                'code' => 0,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
        return $output;
    }
}
