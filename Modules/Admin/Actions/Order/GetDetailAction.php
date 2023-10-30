<?php


namespace Modules\Admin\Actions\Order;


use App\Models\Orders;
use Illuminate\Http\Request;

class GetDetailAction
{
    public function handle(Request $request)
    {
        $order = [];
        $id = $request->route('id');
        if ($id > 0) {
            $order = Orders::where('id', $id)->first();
        }
        return [
            'code' => 1,
            'message' => 'Success!',
            'data' => $order
        ];
    }
}
