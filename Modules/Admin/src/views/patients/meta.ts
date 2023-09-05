import router from "@/router";
import { formConfig } from "@/config";

const prefix = 'patient'
const routerPath = '/' + prefix
// const fetchListApi = function (params) {
//   return Api.get(prefix + '/list', { params });
// };
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
}
