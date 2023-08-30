<?php


namespace Modules\Admin\Actions\Patient;

use App\Models\Patient;
use App\Models\PatientPackage;
use Illuminate\Http\Request;
use Modules\Admin\Middleware\AdminIsAuthenticated;

class PostAction
{
    public function handle(Request $request)
    {
        $data = $request->all();

        try {
            $patient = new Patient();
            if (isset($data['id']) && $data['id'] > 0) {
                $patient = Patient::find($data['id']);
            }

            if (!isset($data['slug']) || $data['slug'] == '') {
                $data['slug'] = \Str::slug($data['name']);
            }

            $patient->fill($data);
            $patient->save();
            if($request->input('images')){
                $syncImages = [];
                foreach($request->input('images') as $imageData){
                    $syncImages[$imageData['id']]=['type'=>$imageData['type']??'image'];
                }
                $patient->images()->sync($syncImages);
            }
            if($data['type'] == 'package'){
                if(isset($data['id']) && $data['id'] > 0){
                    PatientPackage::where('package_id',$data['id'])->delete();
                }
                if(!empty($data['packages'])){
                    foreach($data['packages'] as $v){
                        $pa = [
                            'package_id' => $patient->id,
                            'patient_id' => $v['id'],
                            'patient_descr' => $v
                        ];
                        PatientPackage::create($pa);
                    }
                }
            }

            $output = [
                'code' => 1,
                'message' => 'Thành công!',
                'data' => $patient
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
