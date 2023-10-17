<?php


namespace Modules\Admin\Actions\Inventory;

use App\Models\Inventories;
use Illuminate\Http\Request;

class PostAction
{
    public function handle(Request $request)
    {
        $data = $request->all();

        try {
            $product = new Inventories();
            if (isset($data['id']) && $data['id'] > 0) {
                $product = Inventories::find($data['id']);
            }
            $data['created_by'] = \Auth::guard('admin')->user()->username;
            $product->fill($data);
            $product->save();

            $output = [
                'code' => 1,
                'message' => 'Success!',
                'data' => $product
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
