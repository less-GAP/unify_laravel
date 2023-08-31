<?php


namespace Modules\Admin\Actions\User;


use App\Models\User;
use Illuminate\Http\Request;

class DeleteUserAction
{
    public function handle(Request $request)
    {

        $query = User::query();
        return [
            'result' => $query->find($request->route('id'))->update([
                'delete' => 1,
                'deleted_at' => now(),
                'deleted_by' => auth()->user()->id
            ]),
            'message' => 'Delete User Successfully!'
        ];
    }
}
