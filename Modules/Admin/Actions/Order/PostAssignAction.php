<?php


namespace Modules\Admin\Actions\Order;

use App\Models\Orders;
use Illuminate\Http\Request;

class PostAssignAction
{
    public function handle(Request $request)
    {
        $data = $request->all();
        try {
            $order = Orders::where('id', $data['order_id'])->update([
                'shipping_id' => $data['shipping_id'],
                'shipper_phone' => $data['shipper_phone'],
                'shipper_email' => $data['shipper_email'],
                'shipper_name' => $data['shipper_name']
            ]);

            $output = [
                'code' => 1,
                'message' => 'Success!',
                'data' => $order
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
