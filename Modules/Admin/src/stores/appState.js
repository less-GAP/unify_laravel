import { defineStore } from "pinia";
import Api from "@/utils/Api";
import { ref, watch } from "vue";
// export const useAppStateStore = defineStore("appState", {
//   state: () => ({
//     /* User */
//     showMenu: false,
//     menuCollapsed: false,
//     title: '',
//     currentRoute: {},
//     configs: {},
//   }),
//   actions: {
//     setTitle(title) {
//       this.title = title
//     },
//     setRoute(curentRoute) {
//       this.currentRoute = curentRoute
//     },
//     fetch: function () {
//       Api.get("config", { params: { names: configNames } })
//         .then((r) => {
//           // this.setConfigs(r.data)
//           return r.data;
//         })
//         .catch((error) => {
//           return false;
//         });
//     },
//   },
//   persist: {
//     enabled: true
//   },
// });

export const useAppStateStore = defineStore("appState", () => {
  const showMenu = ref(false);
  const menuCollapsed = ref(false);
  const title = ref('');
  const currentRoute = ref({});
  const configs = ref({});
  const configNames = ["per_page", "default_seller"];
  const setTitle = (newTitle) => {
    title.value = newTitle
  };
  const setRoute = (newCurrentRoute) => {
    currentRoute.value = newCurrentRoute
  };

  async function fetchConfigs() {
    const rs = await Api.get("config", { params: { names: configNames } })
    configs.value = rs.data;
  };

  fetchConfigs();

  return {
    showMenu,
    menuCollapsed,
    title,
    currentRoute,
    configs,
    setTitle,
    setRoute,
  }
});