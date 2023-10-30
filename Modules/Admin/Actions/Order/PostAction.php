<?php


namespace Modules\Admin\Actions\Order;

use App\Models\InventoryDetail;
use App\Models\Orders;
use App\Models\OrderDetails;
use Illuminate\Http\Request;

class PostAction
{
    public function handle(Request $request)
    {
        $data = $request->all();
        try {
            $order = new Orders();
            if (isset($data['id']) && $data['id'] > 0) {
                $order = Orders::find($data['id']);
            }
            $data['created_by'] = \Auth::guard('admin')->user()->username;
            $order->fill($data);
            $order->save();
            if ($order->id > 0) {
                OrderDetails::where('order_id', $order->id)->delete();
                if (!empty($data['products'])) {
                    foreach ($data['products'] as $k => $v) {
                        $amount = $v['amount'];
                        $ins = [
                            'order_id' => $order->id,
                            'product_id' => $v['product_id'],
                            'trademark_id' => $v['trademark_id'],
                            'supplier_id' => $v['supplier_id'],
                            'amount' => $v['amount'],
                        ];
                        OrderDetails::create($ins);

                        //minus stock amount
                        $stocks = InventoryDetail::where('product_id', $v['product_id'])
                            ->where('supplier_id', $v['supplier_id'])
                            ->where('trademark_id', $v['trademark_id'])
                            ->where('type', 'in')
                            ->orderBy('expiration_date', 'ASC')
                            ->get();
                        if (!empty($stocks)) {
                            foreach ($stocks as $st) {
                                if ($amount > $st['remaining']) {
                                    $amount = $amount - $st['remaining'];
                                    InventoryDetail::where('id', $st['id'])->update([
                                        'used' => $st['remaining'],
                                        'remaining' => 0,
                                    ]);
                                } else {
                                    InventoryDetail::where('id', $st['id'])->update([
                                        'used' => $amount,
                                        'remaining' => $st['remaining'] - $amount,
                                    ]);
                                    break;
                                }
                            }
                        }
                    }
                }
            }

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
