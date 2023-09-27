import {acceptHMRUpdate, defineStore} from "pinia";
import Api from "@/utils/Api";
import {ref, watch} from "vue";
// import { useCachedRequest } from './useCachedRequest'
const defaultConfig = {
  "site_title": "",
  "site_description": "",
  "copy_right": "",
  "default_seller": "5",
  "per_page": "50"
}
export const useAppStateStore = defineStore("appState", {
  state: () => ({
    /* User */
    showMenu: false,
    menuCollapsed: false,
    title: '',
    currentRoute: {},
    configs: {...defaultConfig},
  }),
  actions: {
    setTitle(title) {
      this.title = title
    },
    setRoute(curentRoute) {
      this.currentRoute = curentRoute
    },
    async fetchConfig() {
      Api.get("config", {params: {names: Object.keys(defaultConfig)}})
        .then((rs) => {
          this.configs = rs.data
        })
        .catch((error) => {
          return false;
        });
    },
  },
  persist: {
    enabled: true
  },
});
