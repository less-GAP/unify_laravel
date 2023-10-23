<?php


namespace Modules\Admin\Actions\Patient;

use App\Models\Patient;
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
