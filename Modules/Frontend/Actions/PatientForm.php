<?php

namespace Modules\Frontend\Actions;

use Carbon\Carbon;
use Illuminate\Http\Request;

class PatientForm
{
    public function handle(Request $request)
    {
        $config = new \App\Models\Config();
        $data['logo'] = $config->where('key', 'logo')->first();
        
        return view('Frontend::home', [
            'data' => $data
        ]);
    }
}
