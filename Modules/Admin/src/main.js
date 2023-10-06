import {createApp} from "vue";
import {createPinia} from "pinia";

import App from "./App.vue";
import router from "./router";
import {useMainStore} from "@/stores/main.js";
import {useStyleStore} from "@/stores/style.js";
import {darkModeKey, styleKey} from "@/config.js";

import "./css/main.css";

import {useAuthStore} from "@/stores/auth";
import piniaPersist from 'pinia-plugin-persist'
import Api from "@/utils/Api";
import {url} from "@/utils/Url";
import {useTranslation} from "@/utils/Translation";
import Antd from 'ant-design-vue';
import {useAppStateStore} from "@/stores/appState";
import Format from "@/utils/Format";
import Vue3Signature from "vue3-signature"
import * as config from "@/config";
import {useProfileState} from "@/lessgap/stores/ProfileStore";
/* Init Pinia */
const pinia = createPinia();
pinia.use(piniaPersist)
/* Create Vue app */
const myApp = createApp(App).use(Vue3Signature).use(router).use(pinia)
/* Init Pinia stores */
const mainStore = useMainStore(pinia);
const styleStore = useStyleStore(pinia);
const authStore = useAuthStore();
const appState = useAppStateStore();
const profileState = useProfileState();
appState.fetch()
profileState.fetch()
const translation = useTranslation();

myApp.config.globalProperties.$config = config;
myApp.config.globalProperties.$auth = authStore;
myApp.config.globalProperties.$api = Api;
myApp.config.globalProperties.$format = Format;
myApp.config.globalProperties.$style = styleStore;
myApp.config.globalProperties.$appState = appState;
myApp.config.globalProperties.$profileState = profileState;
myApp.config.globalProperties.__ = translation.__;
myApp.config.globalProperties.$url = url;
myApp.use(Antd).mount("#app");
/* Fetch sample data */
// mainStore.fetch("clients");
// mainStore.fetch("history");

/* App style */
//styleStore.setStyle(localStorage[styleKey] ?? "basic");

/* Dark mode */
// if (
//   (!localStorage[darkModeKey] &&
//     window.matchMedia("(prefers-color-scheme: dark)").matches) ||
//   localStorage[darkModeKey] === "1"
// ) {
//   styleStore.setDarkMode(true);
// }

/* Default title tag */
const defaultDocumentTitle = "Unify";

/* Set document title from route meta */
router.afterEach((to) => {
  document.title = to.meta?.title
    ? `${to.meta.title} — ${defaultDocumentTitle}`
    : defaultDocumentTitle;
});
