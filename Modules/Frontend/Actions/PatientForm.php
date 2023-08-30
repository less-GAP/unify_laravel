<?php

namespace Modules\Frontend\Actions;

use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PatientForm
{

    public function handle(Request $request)
    {
        $data['title'] = 'Unifymedsupply';
        $data['logo'] = asset('images/logo.png');
        $data['states'] = json_encode($this->get_states());
        $data['insuranceCoverages'] = json_encode($this->get_insuranceCoverages());

        return view('Frontend::home', [
            'data' => $data
        ]);
    }

    function get_insuranceCoverages(){
        return array(
            array(
                'id' => 'medicaid',
                'name' => 'Medicaid',
                'status' => 1
            ),
            array(
                'id' => 'medicare_a',
                'name' => 'Medicare A',
                'status' => 1
            ),
            array(
                'id' => 'medicare_b',
                'name' => 'Medicare B',
                'status' => 1
            ),
            array(
                'id' => 'hcbs_elderly_blind_&_disabled_waiver',
                'name' => 'HCBS Elderly, Blind & Disabled Waiver',
                'status' => 1
            ),
            array(
                'id' => 'specified_low_income_medicare_beneficiary',
                'name' => 'Specified Low-Income Medicare Beneficiary',
                'status' => 1
            ),
            array(
                'id' => 'behavioral_health_benefits',
                'name' => 'Behavioral Health Benefits',
                'status' => 1
            ),
            array(
                'id' => 'bha_benefit_plan',
                'name' => 'BHA Benefit Plan',
                'status' => 1
            ),
        );
    }

    function get_the_insurance_coverage($id_coverage)
    {
        if (!$id_coverage) return false;
        $coverages = $this->get_insuranceCoverages();
        foreach ($coverages as $coverage) {
            if ($coverage['id'] == $id_coverage) {
                return $coverage;
            }
        }
        return false;
    }

    function get_states()
    {

        return array(
            'AL' => "Alabama",
            'AK' => "Alaska",
            'AZ' => "Arizona",
            'AR' => "Arkansas",
            'CA' => "California",
            'CO' => "Colorado",
            'CT' => "Connecticut",
            'DE' => "Delaware",
            'DC' => "District Of Columbia",
            'FL' => "Florida",
            'GA' => "Georgia",
            'HI' => "Hawaii",
            'ID' => "Idaho",
            'IL' => "Illinois",
            'IN' => "Indiana",
            'IA' => "Iowa",
            'KS' => "Kansas",
            'KY' => "Kentucky",
            'LA' => "Louisiana",
            'ME' => "Maine",
            'MD' => "Maryland",
            'MA' => "Massachusetts",
            'MI' => "Michigan",
            'MN' => "Minnesota",
            'MS' => "Mississippi",
            'MO' => "Missouri",
            'MT' => "Montana",
            'NE' => "Nebraska",
            'NV' => "Nevada",
            'NH' => "New Hampshire",
            'NJ' => "New Jersey",
            'NM' => "New Mexico",
            'NY' => "New York",
            'NC' => "North Carolina",
            'ND' => "North Dakota",
            'OH' => "Ohio",
            'OK' => "Oklahoma",
            'OR' => "Oregon",
            'PA' => "Pennsylvania",
            'RI' => "Rhode Island",
            'SC' => "South Carolina",
            'SD' => "South Dakota",
            'TN' => "Tennessee",
            'TX' => "Texas",
            'UT' => "Utah",
            'VT' => "Vermont",
            'VA' => "Virginia",
            'WA' => "Washington",
            'WV' => "West Virginia",
            'WI' => "Wisconsin",
            'WY' => "Wyoming"
        );
    }

    function get_the_state($id_state)
    {
        if (!$id_state) return false;
        $states = $this->get_states();
        foreach ($states as $key => $state) {
            if ($key == $id_state) {
                return $state;
            }
        }
        return false;
    }
}
