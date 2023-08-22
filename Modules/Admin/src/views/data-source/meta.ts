import Api from "@/utils/Api";
import router from "@/router";
import {formConfig} from "@/config";
import {mdiDelete} from "@mdi/js";
import {notification} from "ant-design-vue";

const prefix = 'data-source'
const fetchListApi = function (params) {
  return Api.get(prefix + '/list', {params})
};
const fetchDetailApi = function (id) {
  return Api.get(prefix + '/' + id)
};
const createApi = function (params) {
  return Api.post(prefix, params)
};
const updateApi = function (id, params) {
  return Api.put(prefix + '/' + id, params)
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
            message: 'Thông báo',
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
      title: 'Name',
      key: 'name'
    },
    {
      title: 'Url',
      key: 'url'
    },


    {
      title: 'Active',
      key: 'status',
      width: 100

    },
  ],
  selectionActions: [
    {
      title: 'Hoạt động',
      action(selectedKeys) {
        return Api.post('product/activeList', {
          'items': selectedKeys,
          'status': 'A'
        }).then(rs => {
          notification[rs.data.code == 0 ? 'error' : 'success']({
            message: 'Thông báo',
            description: rs.data.message,
          });
        })
      },
      // complete() {
      //   alert('success')
      // }
    },
    {
      title: 'Tắt',
      action(selectedKeys) {
        return Api.post('product/activeList', {
          'items': selectedKeys,
          'status': 'D'
        }).then(rs => {
          notification[rs.data.code == 0 ? 'error' : 'success']({
            message: 'Thông báo',
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
  url: '',
  rule_detect_category_link:'//nav//li//a/@href',
  rule_detect_post_link:'//h3/a//@href',
  rule_detect_post_title:'//title//text()',
  rule_detect_post_excerpt:'//meta[@name="description"][1]//@content',
  rule_detect_post_keywords:'//meta[@name="keywords"][1]//@content',
  rule_detect_post_content:'//p/following-sibling::p/following-sibling::p/parent::article[1]',
  rule_detect_post_image:'//meta[@property="og:image"][1]//@content',
  rule_detect_post_tags:'//meta[@property="article:tag"][1]//@content',
  country:'vn',
  craw_frequently_hours:5,
}
const getPostUrl = function (params) {
  return Api.post(prefix+'/postUrls', params)
};const getPostDetail = function (params) {
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
