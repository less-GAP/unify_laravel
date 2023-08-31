import router from "@/router";
import { formConfig } from "@/config";

const prefix = 'patient'
const routerPath = '/' + prefix
// const fetchListApi = function (params) {
//   return Api.get(prefix + '/list', { params });
// };
const fetchListStatesApi = function () {
  // return Api.get('state' + '/list', {params});
  return [
    { code: 'AL', name: 'Alabama' },
    { code: 'AK', name: 'Alaska' },
    { code: 'AZ', name: 'Arizona' },
    { code: 'AR', name: 'Arkansas' },
    { code: 'CA', name: 'California' },
    { code: 'CO', name: 'Colorado' },
    { code: 'CT', name: 'Connecticut' },
    { code: 'DE', name: 'Delaware' },
    { code: 'DC', name: 'District Of Columbia' },
    { code: 'FL', name: 'Florida' },
    { code: 'GA', name: 'Georgia' },
    { code: 'HI', name: 'Hawaii' },
    { code: 'ID', name: 'Idaho' },
    { code: 'IL', name: 'Illinois' },
    { code: 'IN', name: 'Indiana' },
    { code: 'IA', name: 'Iowa' },
    { code: 'KS', name: 'Kansas' },
    { code: 'KY', name: 'Kentucky' },
    { code: 'LA', name: 'Louisiana' },
    { code: 'ME', name: 'Maine' },
    { code: 'MD', name: 'Maryland' },
    { code: 'MA', name: 'Massachusetts' },
    { code: 'MI', name: 'Michigan' },
    { code: 'MN', name: 'Minnesota' },
    { code: 'MS', name: 'Mississippi' },
    { code: 'MO', name: 'Missouri' },
    { code: 'MT', name: 'Montana' },
    { code: 'NE', name: 'Nebraska' },
    { code: 'NV', name: 'Nevada' },
    { code: 'NH', name: 'New Hampshire' },
    { code: 'NJ', name: 'New Jersey' },
    { code: 'NM', name: 'New Mexico' },
    { code: 'NY', name: 'New York' },
    { code: 'NC', name: 'North Carolina' },
    { code: 'ND', name: 'North Dakota' },
    { code: 'OH', name: 'Ohio' },
    { code: 'OK', name: 'Oklahoma' },
    { code: 'OR', name: 'Oregon' },
    { code: 'PA', name: 'Pennsylvania' },
    { code: 'RI', name: 'Rhode Island' },
    { code: 'SC', name: 'South Carolina' },
    { code: 'SD', name: 'South Dakota' },
    { code: 'TN', name: 'Tennessee' },
    { code: 'TX', name: 'Texas' },
    { code: 'UT', name: 'Utah' },
    { code: 'VT', name: 'Vermont' },
    { code: 'VA', name: 'Virginia' },
    { code: 'WA', name: 'Washington' },
    { code: 'WV', name: 'West Virginia' },
    { code: 'WI', name: 'Wisconsin' },
    { code: 'WY', name: 'Wyoming' }
  ];
};
const fetchListInsurancesApi = function () {
  // return Api.get('insurance' + '/list', {params});
  return [
    {
      value: 'medicaid',
      label: 'Medicaid',
      status: 1
    },
    {
      value: 'medicare_a',
      label: 'Medicare A',
      status: 1
    },
    {
      value: 'medicare_b',
      label: 'Medicare B',
      status: 1
    },
    {
      value: 'hcbs_elderly_blind_&_disabled_waiver',
      label: 'HCBS Elderly, Blind & Disabled Waiver',
      status: 1
    },
    {
      value: 'specified_low_income_medicare_beneficiary',
      label: 'Specified Low-Income Medicare Beneficiary',
      status: 1
    },
    {
      value: 'behavioral_health_benefits',
      label: 'Behavioral Health Benefits',
      status: 1
    },
    {
      value: 'bha_benefit_plan',
      label: 'BHA Benefit Plan',
      status: 1
    }
  ];
};
const fetchListDoctorsApi = function () {
  // return Api.get('doctor' + '/list', { params });
  return [
    {
      value: 0,
      label: 'Dr. John Doe',
    },
    {
      value: 1,
      label: 'Dr. Mike Donald',
    },
  ];
}
const fetchListStatusPatientApi = function () {
  // return Api.get('patient_status' + '/list', { params });
  return [
    {
      ID: 'potential_patient',
      name: 'Potential Patient',
      color: '#fff',
      bg_color: '#5b3286',
    },
    {
      ID: 'insurance_pending',
      name: 'Insurance Pending',
      color: '#000',
      bg_color: '#c0e1f6',
    },
    {
      ID: 'new_patient',
      name: 'New Patient',
      color: '#000',
      bg_color: '#e8eaed',
    },
    {
      ID: 'new_supply_request',
      name: 'New Supply Request',
      color: '#000',
      bg_color: '#d0d2d5',
    },
    {
      ID: 'urgent',
      name: 'Urgent',
      color: '#fff',
      bg_color: '#b10202',
    },
    {
      ID: 'pending',
      name: 'Pending',
      color: '#000',
      bg_color: '#ffe59f',
    },
    {
      ID: 'renewal',
      name: 'Renewal',
      color: '#000',
      bg_color: '#ffe59f',
    },
    {
      ID: 'code',
      name: 'Code',
      color: '#fff',
      bg_color: '#116743',
    },
    {
      ID: 'pending_par',
      name: 'Pending PAR',
      color: '#000',
      bg_color: '#c0e1f6',
    },
    {
      ID: 'par',
      name: 'PAR',
      color: '#fff',
      bg_color: '#0953a8',
    },
    {
      ID: 'kepro',
      name: 'Kepro',
      color: '#fff',
      bg_color: '#0953a8',
    },
    {
      ID: 'pending_kepro',
      name: 'Pending Kepro',
      color: '#fff',
      bg_color: '#0953a8',
    },
    {
      ID: 'outbound_document',
      name: 'Outbound document',
      color: '#000',
      bg_color: '#ffc8aa',
    },
    {
      ID: 'review',
      name: 'Review',
      color: '#fff',
      bg_color: '#116743',
    },
    {
      ID: 'done',
      name: 'Done',
      color: '#fff',
      bg_color: '#116743',
    },
    {
      ID: 'dropoff',
      name: 'Dropoff',
      color: '#fff',
      bg_color: '#ffad0d',
    },
    {
      ID: 'new_supply_request',
      name: 'New Supply Request',
      color: '#000',
      bg_color: '#e8eaed',
    },
    {
      ID: 'ready_to_bill',
      name: 'Ready to bill',
      color: '#000',
      bg_color: '#d4edbb',
    },
  ];
}
const fetchListDoctorStatusApi = function () {
  // return Api.get('doctor' + '/list_status', { params });
  return [
    {
      value: 0,
      label: 'Unknow',
    },
    {
      value: 1,
      label: 'Waiting',
    },
    {
      value: 2,
      label: 'Confirm',
    },
    {
      value: 3,
      label: 'Done',
    },
  ];
}
// const fetchDetailApi = function (id) {
//   return Api.get(prefix + '/' + id);
// };
// const createApi = function (params) {
//   console.log(params);
//   return Api.post(prefix, params);
// };
// const updateApi = function (id, params) {
//   return Api.put(prefix + '/' + id, params);
// };
const defaultNewValue = {
  'unify_active': '',
  'unify_status': '',
  'unify_process': '',
  'full_name': '',
  'last_name': '',
  'first_name': '',
  'dob': '',
  'height': '',
  'weight': '',
  'gender': '',
  'insurance_coverages': '',
  'email': '',
  'phone': '',
  'street': '',
  'apt': '',
  'city': '',
  'state': '',
  'zip': '',
  'route': '',
  'sub_r': '',
  'supplies': '',
  'products': '',
  'note': '',
  'unify_data': '',
  'doctor_id': '',
  'doctor_status': '',
  'doctor_comment': '',
  'service_dates': '',
  'need_improve': '',
  'unify_deleted': '',
  'unify_deleted_at': '',
  'unify_deleted_by': '',
  'sale_user': '',
}
const back = () => {
  router.push('/' + prefix);
};
export {
  back,
  routerPath,
  defaultNewValue,
  // createApi,
  // updateApi,
  formConfig,
  // fetchListApi,
  // fetchDetailApi,
  fetchListStatesApi,
  fetchListInsurancesApi,
  fetchListDoctorsApi,
  fetchListDoctorStatusApi,
  fetchListStatusPatientApi,
}
