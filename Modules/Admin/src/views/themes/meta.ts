import Api from "@/utils/Api";
import router from "@/router";
import {formConfig} from "@/config";
import {mdiDelete} from "@mdi/js";
import {notification} from "ant-design-vue";

const prefix = 'themes'
const fetchListApi = function (params) {
  return Api.get(prefix + '/list', {params})
};
const fetchDetailApi = function (id) {
  return Api.get(prefix + '/' + id)
};
const createApi = function (params) {
  let formData = new FormData();
  formData.append("file", params.file);
  formData.append("name", params.name);
  formData.append("image", params.image);
  return Api.post(prefix, formData,{
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
};
const updateApi = function (id, params) {
  return Api.put(prefix + '/' + id, params,{
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
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
      title: 'Image',
      key: 'image'
    },
    {
      title: 'Name',
      key: 'name'
    }

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
  name: '',
  image: '',
  file: '',
  filename: '',
}
const getPostUrl = function (params) {
  return Api.post(prefix+'/postUrls', params)
};
const getPostDetail = function (params) {
  return Api.post(prefix+'/postDetail', params)
};
export {
  defaultNewValue,
  getPostUrl,
  getPostDetail,
  createApi,
  updateApi,
  formConfig,
  fetchListApi,
  fetchDetailApi,
  tableConfig
}
