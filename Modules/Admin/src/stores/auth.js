import {defineStore} from "pinia";
import Api from "@/utils/Api";
import router from "@/router";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    /* User */
    user: null,

  }),
  actions: {
    setUser(user) {
      this.user = user
    },
    hasPermission(permission) {
      const user = this.user
      if (!user && permission) {
        return false;
      }
      if (!permission || user?.app_permissions?.includes('*')) {
        return true
      }

      const checkArr = permission.split('.')
      if (checkArr.length > 1) {
        return user.app_permissions.includes('*') || user.app_permissions.includes(permission) || user.app_permissions.includes(checkArr[0] + '.*');
      }
      return user.app_permissions.includes(permission+'.*') || user.app_permissions.includes(permission);
    },
    logout() {
      this.$reset()
      router.push('login')
    },
    isLogin() {
      return this.user != null
    },
    async login(data) {
      const res = await Api
        .post(`auth/login`, data)
      this.setUser(res.data)
      return res;

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
