<?php


namespace Modules\Admin\Actions\Supplier;

use App\Models\Supplier;
use Illuminate\Http\Request;

class PostAction
{
    public function handle(Request $request)
    {
        $data = $request->all();

        try {
            $product = new Supplier();
            if (isset($data['id']) && $data['id'] > 0) {
                $product = Supplier::find($data['id']);
            }

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
