import axios from 'axios'
import {useAuthStore} from "@/stores/auth";
import {message} from 'ant-design-vue';

const Api = axios.create({
  baseURL: import.meta.env.VITE_API_HOST ? import.meta.env.VITE_API_HOST : '' + '/api/',
  withCredentials: true
})
let key = 'request_updatable';
let requestConfig = null;
let hideMessage = null;

Api.interceptors.request.use(function (config) {
  // Do something before request is sent
  requestConfig = config
  if (requestConfig.method.toLowerCase() == 'post' || requestConfig.method.toLowerCase() == 'put') {
    hideMessage = message.loading({content: 'Submit...', key, duration: 1});
  }
  // if (requestConfig.method.toLowerCase() == 'get') {
  //   hideMessage = message.loading({content: 'Loading...', key, duration: 10});
  // }
  return config;
}, function (error) {
  // Do something with request error
  return Promise.reject(error);
});

Api.interceptors.response.use((response) => {
  //console.log(response);
  if (response?.data?.message && (requestConfig.method.toLowerCase() == 'post' || requestConfig.method.toLowerCase() == 'put')) {
    if(response?.data?.code == 0){
      message.error({content: response?.data?.message, key, duration: 1});
    }else{
      message.success({content: response?.data?.message, key, duration: 1});
    }
  } else if (requestConfig.method.toLowerCase() == 'delete') {
    message.info({content: response?.data?.message, key, duration: 1});
  } else if (hideMessage) {
    hideMessage()
  }
  return response;
}, (error) => {
  message.error({content: error.response?.data?.message || 'Error!', key, duration: 1});
  if (error.response.status == 401) {
    useAuthStore().logout()
  }
  return Promise.reject(error);
});
export default Api
