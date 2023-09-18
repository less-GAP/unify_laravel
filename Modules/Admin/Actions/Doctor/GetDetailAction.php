<?php


namespace Modules\Admin\Actions\Doctor;


use App\Models\Doctor;
use App\Providers\AbstractModuleProvider;
use Illuminate\Http\Request;

class GetDetailAction
{
    public function handle(Request $request)
    {
        $doctor = [];
        $id = $request->route('id');
        if($id > 0){
            $doctor = Doctor::where('id',$id)->first();
            return [
                'code' => 1,
                'message' => 'Success!',
                'data' => $doctor->load('patients'),
            ];
        }
        return [
            'code' => 0,
            'message' => 'Error!',
            'data' => $doctor
        ];
    }
}
