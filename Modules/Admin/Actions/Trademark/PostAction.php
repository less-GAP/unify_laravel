<?php


namespace Modules\Admin\Actions\Trademark;

use App\Models\Trademark;
use Illuminate\Http\Request;

class PostAction
{
    public function handle(Request $request)
    {
        $data = $request->all();

        try {
            $product = new Trademark();
            if (isset($data['id']) && $data['id'] > 0) {
                $product = Trademark::find($data['id']);
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
