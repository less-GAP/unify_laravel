<?php


namespace Modules\Admin\Actions\Inventory;

use App\Models\Inventories;
use App\Models\InventoryDetail;
use Illuminate\Http\Request;

class PostAction
{
    public function handle(Request $request)
    {
        $data = $request->all();

        try {
            $inventory = new Inventories();
            if (isset($data['id']) && $data['id'] > 0) {
                $inventory = Inventories::find($data['id']);
            }
            $data['created_by'] = \Auth::guard('admin')->user()->username;
            $inventory->fill($data);
            $inventory->save();
            if ($inventory->id > 0) {
                InventoryDetail::where('inventory_id', $inventory->id)->delete();
                if (!empty($data['products'])) {
                    foreach ($data['products'] as $k => $v) {
                        $ins = [
                            'inventory_id' => $inventory->id,
                            'product_id' => $v['product_id'],
                            'trademark_id' => $v['trademark_id'],
                            'amount' => $v['amount'],
                            'expiration_date' => $v['expiration_date'],
                            'used' => 0,
                            'remaining' => $v['amount'],
                            'order_id' => null,
                            'type' => $data['type'],
                            'supplier_id' => $data['supplier_id']
                        ];
                        InventoryDetail::create($ins);
                    }
                }
            }

            $output = [
                'code' => 1,
                'message' => 'Success!',
                'data' => $inventory
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
