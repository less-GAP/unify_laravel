<?php


namespace Modules\Admin\Actions\Order;


use App\Models\InventoryDetail;
use App\Models\OrderDetailInventories;
use App\Models\OrderDetails;
use App\Models\Orders;
use App\Models\Product;
use Illuminate\Http\Request;

class DeleteAction
{
    public function handle(Request $request)
    {
        try {
            $query = Orders::find($request->route('id'));
            $del = $query->delete();
            if ($del) {
                $details = OrderDetails::where('order_id', $request->route('id'))->get();
                if (!empty($details)) {
                    foreach ($details as $de) {
                        $inven = OrderDetailInventories::where('order_id', $de['order_id'])
                            ->where('order_detail_id', $de['id'])
                            ->get();
                        if (!empty($inven)) {
                            foreach ($inven as $inv) {
                                $inven_detail = InventoryDetail::where('id', $inv['inventory_id'])->first();
                                if (!empty($inven_detail)) {
                                    //cong lai ton kho
                                    InventoryDetail::where('id', $inv['inventory_id'])->update([
                                        'used' => $inven_detail['used'] - $inv['amount'],
                                        'remaining' => $inven_detail['remaining'] + $inv['amount'],
                                    ]);
                                    //xoa record
                                    OrderDetailInventories::where('id', $inv['id'])->delete();
                                }
                            }
                            OrderDetails::where('id', $de['id'])->delete();
                        }
                    }
                }
            }
            $output = [
                'code' => 1,
                'message' => 'Success!',
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
