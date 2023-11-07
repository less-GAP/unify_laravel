<?php


namespace Modules\Admin\Actions\Order;


use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetListAction
{
    public function handle(Request $request)
    {
        $query = Orders::with('details');
        $user = auth('admin')->user();

        if (!$user->hasPermissionTo('Order.AllData')) {
            $query->where('shipping_id', $user->id);
        }
        $filter = $request->filter;
        if ($filter) {
            if (isset($filter['search'])) {
                $query->Where('email', 'like', '%' . $filter['search'] . '%')
                    ->orWhere('phone', 'like', '%' . $filter['search'] . '%');
            }
        }

        if ($request->input('sort') !== null) {
            switch ($request->input('sort')) {
                case '-id':
                    $query->orderBy('id', $request->input('order', 'DESC'));
                    break;
                case 'id':
                    $query->orderBy('id', $request->input('order', 'ASC'));
                    break;
                case '-updated_at':
                    $query->orderBy('updated_at', $request->input('order', 'DESC'));
                    break;
                default:
                    $query->orderBy('id', $request->input('order', 'DESC'));
                    break;
            }
        } else {
            $query->orderBy('id', $request->input('order', 'DESC'));
        }

        return $query->paginate($request->input('perPage', 15));
    }
}
