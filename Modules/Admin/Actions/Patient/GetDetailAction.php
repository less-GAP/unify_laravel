<?php


namespace Modules\Admin\Actions\Patient;


use App\Models\Patient;
use App\Providers\AbstractModuleProvider;
use Illuminate\Http\Request;

class GetDetailAction
{
    public function handle(Request $request)
    {
        $patient = [];
        $id = $request->route('id');
        if($id > 0){
            $patient = Patient::where('id',$id)->first();
        }

        return [
            'code' => 1,
            'message' => 'Success!',
            'data' => $patient->load('images')
        ];
    }
}
