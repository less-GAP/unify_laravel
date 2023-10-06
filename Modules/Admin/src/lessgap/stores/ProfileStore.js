import {acceptHMRUpdate, defineStore} from "pinia";
import Api from "@/utils/Api";
import {db} from "@/utils/RealtimeDB";
import {ref, watch} from "vue";
// import { useCachedRequest } from './useCachedRequest'

export const useProfileState = defineStore("profileState", {
  state: () => ({
    notification_version: '',
  }),
  actions: {
    async fetch() {
      await db.live(
        "profile_versions",
        // The callback function takes an object with the "action" and "result" properties
        ({action, result}) => {
          // action can be: "CREATE", "UPDATE", "DELETE" or "CLOSE"
          if (action === "CLOSE") return;
          // result contains either the entire record, or a set of JSON patches when diff mode is enabled
          try {
            console.log('versions', result)
            this.notification_version = result.value
          } catch (e) {
            console.log(e)
          }
        })

    },
  },
  persist: {
    enabled: true
  },
});
