<?php

namespace App\Console\Commands;

use App\Models\InventoryDetail;
use App\Models\OrderDetailInventories;
use App\Models\OrderDetails;
use App\Models\Orders;
use App\Models\Patient;
use Illuminate\Console\Command;

class AutoCreateOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AutoCreateOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $days = settings('auto_create_order_days') ?? 0;

        //lay danh sach cac patient da active
        $patients = Patient::where('unify_process', 2)->get();
        if (!empty($patients)) {
            foreach ($patients as $k => $v) {
                $products = $v->products;
                if (!empty($products)) {
                    foreach ($products as $pro) {
                        switch ($pro['delivery_type']) {
                            case 'one_times':
                                $delivery_date = \Carbon\Carbon::parse($pro['delivery_date']);
                                $now = \Carbon\Carbon::now();
                                //check create order  delivery_date
                                $diff = $now->diffInDays($delivery_date);
                                if ($diff <= $days) {
                                    $this->create_order($v->toArray(), $pro->toArray(), $delivery_date->format('Y-m-d'));
                                }
                                break;
                            case 'weekly':
                                $weeks = [
                                    1 => 'monday',
                                    2 => 'tuesday',
                                    3 => 'wednesday',
                                    4 => 'thursday',
                                    5 => 'friday',
                                    6 => 'saturday',
                                    7 => 'sunday',
                                ];
                                $date = \Carbon\Carbon::parse('this ' . $weeks[$pro['delivery_value']]);
                                $now = \Carbon\Carbon::now();
                                $diff = $now->diffInDays($date);
                                if ($date->format('Y-m-d') > $now->format('Y-m-d') && $diff <= $days) {
                                    $this->create_order($v->toArray(), $pro->toArray(), $date);
                                }
                                break;
                            case 'monthly':
                                $date = \Carbon\Carbon::parse(date('Y-m-' . $pro['delivery_value']));
                                $now = \Carbon\Carbon::now();
                                if ($date->format('Y-m-d') < $now->format('Y-m-d')) {
                                    $date->addMonth();
                                }

                                $diff = $now->diffInDays($date);
                                if ($diff <= $days) {
                                    $this->create_order($v->toArray(), $pro->toArray(), $date);
                                }
                                break;
                            case 'yearly':
                                $date = \Carbon\Carbon::parse($pro['delivery_date']);
                                $now = \Carbon\Carbon::now();
                                if ($date->format('Y-m-d') < $now->format('Y-m-d')) {
                                    $date->addYear();
                                }
                                //check create order  delivery_date
                                $diff = $now->diffInDays($date);
                                if ($diff <= $days) {
                                    $this->create_order($v->toArray(), $pro->toArray(), $date->format('Y-m-d'));
                                }
                                break;
                        }
                    }
                }
            }
        }
    }

    public function create_order($patient, $product, $delivery_date)
    {
        $product_id = $product['product_id'];
        //check order
        $check = Orders::with('details')
            ->where('delivery_date', $delivery_date)
            ->whereHas('details', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            })
            ->first();
        if (empty($check)) {
            $order_data = [
                'status' => 'new',
                'patient_id' => $patient['id'],
                'delivery_date' => $delivery_date,
                'apt' => $patient['apt'],
                'city' => $patient['city'],
                'phone' => $patient['phone'],
                'email' => $patient['email'],
                'route' => $patient['route'],
                'state' => $patient['state'],
                'street' => $patient['street'],
                'sub_r' => $patient['sub_r'],
                'zip' => $patient['zip']
            ];
            $order_data['created_by'] = 'system';
            $order = Orders::create($order_data);
            if ($order->id > 0 && !empty($product)) {
                $stock = InventoryDetail::where('product_id', $product['product_id'])
                    ->where('type', 'in')
                    ->orderBy('expiration_date', 'ASC')
                    ->sum('remaining');

                if ($stock < $product['amount']) {
                    return false;
                }
                $amount = $product['amount'];

                $stocks = InventoryDetail::where('product_id', $product['product_id'])
                    ->where('type', 'in')
                    ->orderBy('expiration_date', 'ASC')
                    ->get();
                if (!empty($stocks)) {
                    foreach ($stocks as $st) {
                        if ($amount > $st['remaining']) {
                            $amount = $amount - $st['remaining'];

                            $ins = [
                                'order_id' => $order->id,
                                'product_id' => $st['product_id'],
                                'trademark_id' => $st['trademark_id'],
                                'supplier_id' => $st['supplier_id'],
                                'amount' => $st['remaining'],
                            ];
                            $detail = OrderDetails::create($ins);


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
                            $ins = [
                                'order_id' => $order->id,
                                'product_id' => $st['product_id'],
                                'trademark_id' => $st['trademark_id'],
                                'supplier_id' => $st['supplier_id'],
                                'amount' => $amount,
                            ];
                            $detail = OrderDetails::create($ins);

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
}
