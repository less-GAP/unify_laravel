import {
  createRouter,
  createWebHashHistory
} from "vue-router";
import Home from "@/views/HomeView.vue";
import {
  useAuthStore
} from "@/stores/auth";
import {
  useAppStateStore
} from "@/stores/appState";

const publicPages = ['/login'];

let routes = [{
    meta: {
      isPublic: true,
    },
    path: "/",
    name: "form",
    component: () => import("@/views/eligibility-check/index.vue"),
  },
  {
    meta: {
      title: "Thank You",
      isPublic: true,
    },
    path: "/thank-you",
    name: "thank-you",
    component: () => import("@/views/eligibility-check/thank.vue"),
  },
  {
    meta: {
      title: "Dashboard",
    },
    path: "/dashboard",
    name: "homedashboard",
    component: Home,
  },
  {
    meta: {
      title: "File Manager",
    },
    path: "/files",
    name: "files",
    component: () => import("@/views/files/index.vue"),
  },
  {
    meta: {
      title: "About",
    },
    path: "/about",
    name: "about",
    component: () => import("@/views/about/index.vue"),
  },
  {
    meta: {
      title: "Posts",
    },
    path: "/post",
    name: "post",
    component: () => import("@/views/posts/index.vue"),
  },
  {
    meta: {
      title: "Posts",
    },
    path: "/post/:id",
    name: "post-detail",
    component: () => import("@/views/posts/Detail.vue"),
  },
  {
    meta: {
      title: "FAQs",
    },
    path: "/faqs",
    name: "faqs",
    component: () => import("@/views/faqs/index.vue"),
  },
  {
    meta: {
      title: "FAQs Detail",
    },
    path: "/faqs/:id",
    name: "faqs-detail",
    component: () => import("@/views/faqs/Detail.vue"),
  },

  {
    meta: {
      title: "Customer",
    },
    path: "/customers",
    name: "customers",
    component: () => import("@/views/customers/index.vue"),
  },
  {
    meta: {
      title: "Customer",
    },
    path: "/customer-group",
    name: "customer-group",
    component: () => import("@/views/customer-group/index.vue"),
  },
  {
    meta: {
      title: "Customer",
    },
    path: "/customer-group/:id",
    name: "customer-group-detail",
    component: () => import("@/views/customer-group/Detail.vue"),
  },

  {
    meta: {
      title: "Login",
      isPublic: true,
    },
    path: "/login",
    name: "login",
    component: () => import("@/views/LoginView.vue"),
  },
];
const modules =
  import.meta.globEager('./../views/**/router.js');

Object.keys(modules).forEach((key) => {
  const mod = modules[key].default || {};
  routes = [...routes, ...mod]
});
const router = createRouter({
  history: createWebHashHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || {
      top: 0
    };
  },
});
router.beforeEach(async (to) => {
  // redirect to login page if not logged in and trying to access a restricted page
  const auth = useAuthStore();
  if (!auth.hasPermission(to.meta?.permission)) {
    // alert('Permission Denied');
    return '';
  }
  if (!to.meta.isPublic && !auth.isLogin()) {
    auth.returnUrl = to.fullPath;
    return '/login';
  }
  useAppStateStore().setRoute(to)
  useAppStateStore().setTitle(to.meta.title)
});
export default router;
