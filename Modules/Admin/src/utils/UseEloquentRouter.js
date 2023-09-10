import Api from "@/utils/Api";
import {notification} from "ant-design-vue";

export function UseEloquentRouter(prefix, options = {}) {
  const fetchListApi = function (params) {
    return Api.get(prefix + '/list', {params: {...options, ...params}})
  };
  const fetchDetailApi = function (id) {
    return Api.get(prefix + '/' + id)
  };
  const fetchRelationShipApi = function (id, relation, params) {
    return Api.get(prefix + '/' + id + '/' + relation, params)
  };
  const attachRelationShipApi = function (id, relation, relationId) {
    return Api.post(prefix + '/' + id + '/' + relation + '/' + relationId)
  };
  const detachRelationShipApi = function (id, relation, relationId) {
    return Api.delete(prefix + '/' + id + '/' + relation + '/' + relationId)
  };
  const createApi = function (params) {
    return Api.post(prefix, params)
  };
  const updateApi = function (id, params) {
    return Api.put(prefix + '/' + id, params)
  };
  const deleteApi = function (id) {
    return Api.delete(prefix + '/' + id)
  };
  return {
    fetchListApi,
    attachRelationShipApi,
    detachRelationShipApi,
    fetchRelationShipApi,
    fetchDetailApi,
    createApi,
    deleteApi,
    updateApi
  }
}
