<?php


namespace Modules\Admin\Actions\Product;


use App\Models\Product;
use Illuminate\Http\Request;

class DeleteProductAction
{
    public function handle(Request $request)
    {
        try {
            $query = Product::query();
            $del = $query->find($request->route('id'))->delete();
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
