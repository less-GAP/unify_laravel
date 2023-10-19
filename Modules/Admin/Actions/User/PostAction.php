<?php


namespace Modules\Admin\Actions\User;


use App\Models\Inventories;
use App\Models\InventoryDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Admin\Requests\CreateUserRequest;

class PostAction
{
    public function handle(CreateUserRequest $request)
    {
        $data = $request->all();


        try {
            if (!empty($data['password'])) {
                $data['password'] = \Hash::make($data['password']);
            }

            if (isset($data['id']) && $data['id'] > 0) {
                $user = User::find($data['id']);
            } else {
                $user = new User();
            }

            $data['created_by'] = \Auth::guard('admin')->user()->username;
            $user->fill($data);
            $user->save();
            if ($user->id > 0) {
                if (!empty($data['roles_id'])) {
                    $user->syncRoles($data['roles_id']);
                }
            }

            $output = [
                'code' => 1,
                'message' => 'Success!',
                'data' => $user
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
