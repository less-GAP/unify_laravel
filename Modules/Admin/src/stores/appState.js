import {acceptHMRUpdate, defineStore} from "pinia";
import Api from "@/utils/Api";
import {db} from "@/utils/RealtimeDB";
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
    versions: {},
    statistics: {},
  }),
  actions: {
    setTitle(title) {
      this.title = title
    },
    setRoute(currentRoute) {
      this.currentRoute = currentRoute
    },
    async fetch() {
      await db.live(
        "versions",
        // The callback function takes an object with the "action" and "result" properties
        ({action, result}) => {
          // action can be: "CREATE", "UPDATE", "DELETE" or "CLOSE"
          if (action === "CLOSE") return;
          // result contains either the entire record, or a set of JSON patches when diff mode is enabled
          this.versions[result.key] = result.value
        })
      await db.live(
        "statistics",
        // The callback function takes an object with the "action" and "result" properties
        ({action, result}) => {
          // action can be: "CREATE", "UPDATE", "DELETE" or "CLOSE"
          if (action === "CLOSE") return;
          // result contains either the entire record, or a set of JSON patches when diff mode is enabled

          this.statistics[result.key] = result.value
        })
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
