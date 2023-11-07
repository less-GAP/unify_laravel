<?php


namespace Modules\Admin\Actions\Order;

use App\Models\Orders;
use Illuminate\Http\Request;

class PostUpdateStatusAction
{
    public function handle(Request $request)
    {
        $data = $request->all();
        try {
            $res = Orders::find($data['id'])->update([
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
