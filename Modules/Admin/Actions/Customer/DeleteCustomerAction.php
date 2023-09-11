<?php


namespace Modules\Admin\Actions\Customer;


use App\Models\Customer;
use Illuminate\Http\Request;

class DeleteCustomerAction
{
    public function handle(Request $request)
    {

        $query = Customer::query();
        return [
            'result' =>$query->find($request->route('id'))->delete(),
            'message' => 'Delete Customer Successfully!'
        ];
    }
}
