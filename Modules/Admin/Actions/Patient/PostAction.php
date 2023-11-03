<?php


namespace Modules\Admin\Actions\Patient;

use App\Models\Patient;
use App\Models\PatientInsurances;
use App\Models\PatientProducts;
use Illuminate\Http\Request;
use Modules\Admin\Middleware\AdminIsAuthenticated;

class PostAction
{
    public function handle(Request $request)
    {
        $data = $request->all();

        //check unify number
        $query = Patient::where('unify_number', $data['unify_number']);
        if (isset($data['id']) && $data['id'] > 0) {
            $query->where('id', '<>', $data['id']);
        }
        $check = $query->first();
        if (empty($check)) {
            try {
                $patient = new Patient();
                if (isset($data['id']) && $data['id'] > 0) {
                    $patient = Patient::find($data['id']);
                }

                $data['full_name'] = $data['first_name'] . ' ' . $data['last_name'];

                $patient->fill($data);
                $patient->save();

                if (!empty($data['products'])) {
                    PatientProducts::where('patient_id', $patient->id)->delete();
                    foreach ($data['products'] as $k => $v) {
                        PatientProducts::create([
                            'patient_id' => $patient->id,
                            'product' => $v['product'],
                            'product_id' => $v['product_id'],
                            'delivery_date' => $v['delivery_date'] ?? null,
                            'delivery_value' => $v['delivery_value'] ?? null,
                            'delivery_type' => $v['delivery_type'],
                            'delivery_option' => $v['delivery_option'],
                            'amount' => $v['amount']
                        ]);
                    }
                }

                if (!empty($data['insurances'])) {
                    PatientInsurances::where('patient_id', $patient->id)->delete();
                    foreach ($data['insurances'] as $k => $v) {
                        PatientInsurances::create([
                            'patient_id' => $patient->id,
                            'insurance_id' => $v['insurance_id'],
                            'coverage' => $v['coverage'],
                            'active_date' => $v['active_date'],
                            'expired_date' => $v['expired_date']
                        ]);
                    }
                }


                // if($request->input('images')){
                //     $syncImages = [];
                //     foreach($request->input('images') as $imageData){
                //         $syncImages[$imageData['id']]=['type'=>$imageData['type']??'image'];
                //     }
                //     $patient->images()->sync($syncImages);
                // }

                $output = [
                    'code' => 1,
                    'message' => 'Success!',
                    'data' => $patient
                ];
            } catch (\Throwable $e) {
                $output = [
                    'code' => 0,
                    'message' => $e->getMessage(),
                    'data' => []
                ];
            }
        } else {
            $output = [
                'code' => 0,
                'message' => 'ID number already exist!',
                'data' => []
            ];
        }
        return $output;
    }
}
