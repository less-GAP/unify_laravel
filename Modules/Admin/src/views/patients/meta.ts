import Api from "@/utils/Api";
import router from "@/router";
import { formConfig } from "@/config";
import { mdiDelete } from "@mdi/js";
import { notification } from "ant-design-vue";

const prefix = 'patient'
const routerPath = '/' + prefix
const fetchListApi = function (params) {
  return Api.get(prefix + '/list', { params });
};
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
      value: '0',
      label: 'Dr. John Doe',
    },
    {
      value: '1',
      label: 'Dr. Mike Donald',
    },
  ];
}
const fetchListDoctorStatusApi = function () {
  // return Api.get('doctor' + '/list_status', { params });
  return [
    {
      value: '0',
      label: 'Unknow',
    },
    {
      value: '1',
      label: 'Waiting',
    },
    {
      value: '2',
      label: 'Confirm',
    },
    {
      value: '3',
      label: 'Done',
    },
  ];
}
const fetchDetailApi = function (id) {
  return Api.get(prefix + '/' + id);
};
const createApi = function (params) {
  console.log(params);
  return Api.post(prefix, params);
};
const updateApi = function (id, params) {
  return Api.put(prefix + '/' + id, params);
};
const tableConfig = {
  tableConfig: {
    sticky: true,
    scroll: "{ x: 1500, y: 300 }",
    bordered: true,
    showHeader: true
  },
  api: fetchListApi,
  addAction: (reload) => {
    //showEditUser({}, reload)
    router.push('/' + prefix + '/new')
  },
  itemActions: [
    {
      label: '',
      class: 'font-medium text-blue-600 dark:text-blue-500 hover:underline',
      icon: mdiDelete,
      key: 'edit',
      action(item, reload) {
        router.push('/' + prefix + '/' + item.id)
      }
    },
    {
      label: '',
      class: 'font-medium text-red-600 dark:text-red-500 hover:underline',
      icon: mdiDelete,
      key: 'delete',
      action(item, reload) {
        Api.delete(prefix + '/' + item.id).then(rs => {
          notification[rs.data.code == 0 ? 'error' : 'success']({
            message: 'Notification',
            description: rs.data.message,
          });
        }).finally(() => {
          reload();
        });
      }
    }

  ],
  columns: [
    {
      title: 'PATIENT ID',
      key: 'id',
      width: '60px'
    },
    {
      title: 'STATUS',
      key: 'status',
      width: '60px'
    },
    {
      title: 'NAME',
      key: 'full_name',
      width: '60px'
    },
    {
      title: 'DOB (M-D-Y)',
      key: 'dob',
      width: '60px'
    },
    {
      title: 'PHONE',
      key: 'phone',
      width: '60px'
    },
    {
      title: 'ADDRESS',
      key: 'address',
      width: '60px'
    },
    {
      title: 'CITY',
      key: 'city',
      width: '60px'
    },
    {
      title: 'ZIPCODE',
      key: 'zipcode',
      width: '60px'
    },
    {
      title: 'DATE ACTIVE',
      key: 'date_active',
      width: '60px'
    },
    {
      title: 'CREATED AT',
      key: 'created_at',
      width: '60px'
    },
  ],
  selectionActions: [
    {
      title: 'Active',
      action(selectedKeys) {
        return Api.post('product/activeList', {
          'items': selectedKeys,
          'status': 'A'
        }).then(rs => {
          notification[rs.data.code == 0 ? 'error' : 'success']({
            message: 'Notification',
            description: rs.data.message,
          });
        })
      },
      // complete() {
      //   alert('success')
      // }
    },
    {
      title: 'Deactive',
      action(selectedKeys) {
        return Api.post('product/activeList', {
          'items': selectedKeys,
          'status': 'D'
        }).then(rs => {
          notification[rs.data.code == 0 ? 'error' : 'success']({
            message: 'Notification',
            description: rs.data.message,
          });
        })
      },
      // complete() {
      //   alert('success')
      // }
    },
    // {
    //   title: 'Delete',
    //   action(selectedKeys) {
    //     return Api.post('user/deleteList', selectedKeys)
    //   },
    //   complete() {
    //     alert('success')
    //   }
    // }
  ]
}
const defaultNewValue = {
  'unify_active': '',
  'unify_status': 0,
  'unify_process': 0,
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
  createApi,
  updateApi,
  formConfig,
  fetchListApi,
  fetchDetailApi,
  fetchListStatesApi,
  fetchListInsurancesApi,
  fetchListDoctorsApi,
  fetchListDoctorStatusApi,
  tableConfig
}
