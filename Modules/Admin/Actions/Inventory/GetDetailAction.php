<?php


namespace Modules\Admin\Actions\Inventory;


use App\Models\Inventories;
use Illuminate\Http\Request;

class GetDetailAction
{
    public function handle(Request $request)
    {
        $inventory = [];
        $id = $request->route('id');
        if ($id > 0) {
            $inventory = Inventories::where('id', $id)->first();
        }
        return [
            'code' => 1,
            'message' => 'Success!',
            'data' => $inventory
        ];
    }
}
