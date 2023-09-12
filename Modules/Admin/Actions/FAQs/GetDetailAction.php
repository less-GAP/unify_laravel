<?php


namespace Modules\Admin\Actions\FAQs;


use App\Models\FAQs;
use App\Providers\AbstractModuleProvider;
use Illuminate\Http\Request;

class GetDetailAction
{
    public function handle(Request $request)
    {
        $page = [];
        $id = $request->route('id');
        if ($id > 0) {
            $page = FAQs::where('id', $id)->first();
        }
        return [
            'code' => 1,
            'message' => 'Update success !',
            'data' => $page
        ];
    }
}
