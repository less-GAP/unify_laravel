<?php


namespace Modules\Admin\Actions\Order;

use App\Models\InventoryDetail;
use App\Models\OrderDetailInventories;
use App\Models\Orders;
use App\Models\OrderDetails;
use Illuminate\Http\Request;

class PostCheckStockAction
{
    public function handle(Request $request)
    {
        $data = $request->all();
        try {
            $inven_detail = InventoryDetail::where('product_id', $data['product_id'])
                ->where('supplier_id', $data['supplier_id'])
                ->where('trademark_id', $data['trademark_id'])
                ->sum('remaining');
            if ($data['amount'] - $inven_detail > 0) {
                $output = [
                    'code' => 0,
                    'message' => 'Insufficient inventory !',
                    'data' => $inven_detail
                ];
            } else {
                $output = [
                    'code' => 1,
                    'message' => 'Success!',
                    'data' => $inven_detail
                ];
            }
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
