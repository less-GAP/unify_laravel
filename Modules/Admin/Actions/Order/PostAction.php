<?php


namespace Modules\Admin\Actions\Order;

use App\Models\InventoryDetail;
use App\Models\OrderDetailInventories;
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
                $order_details = OrderDetails::where('order_id', $order->id)->get();
                if (!empty($order_details)) {
                    foreach ($order_details as $de) {
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
                        $detail = OrderDetails::create($ins);

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

                                    OrderDetailInventories::create([
                                        'order_id' => $order->id,
                                        'order_detail_id' => $detail->id,
                                        'amount' => $amount,
                                        'inventory_id' => $st['id'],
                                    ]);
                                } else {
                                    InventoryDetail::where('id', $st['id'])->update([
                                        'used' => $amount,
                                        'remaining' => $st['remaining'] - $amount,
                                    ]);

                                    OrderDetailInventories::create([
                                        'order_id' => $order->id,
                                        'order_detail_id' => $detail->id,
                                        'amount' => $amount,
                                        'inventory_id' => $st['id'],
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
