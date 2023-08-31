import {defineStore} from "pinia";
import Api from "./Api";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    /* User */
    user: null,

  }),
  actions: {
    isLogin() {
      return this.user != null
    },
    fetch: function () {
      Api
        .get(`userInfo`)
        .then((r) => {
          console.log(r)
          // this.setUser(r.data)
        })
        .catch((error) => {
          alert(error.message);
        });
    },
  },
  persist: {
    enabled: true
  }
});
