import {createRouter, createWebHashHistory} from "vue-router";
import Home from "@/views/HomeView.vue";
import {useAuthStore} from "@/stores/auth";
import {useAppStateStore} from "@/stores/appState";

const publicPages = ['/login'];

let routes = [
  {
    meta: {
      title: "Form",
      isPublic: true,
    },
    path: "/",
    name: "form",
    component: () => import("@/views/eligibility-check/index.vue"),
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
      title: "Configs",
    },
    path: "/configs",
    name: "configs",
    component: () => import("@/views/configs/ConfigLayout.vue"),
    children: [
      {
        meta: {
          title: "General Configuration",
        },
        path: "/configs/general",
        name: "config-general",
        component: () => import("@/views/configs/GeneralConfig.vue"),
      },
      {
        meta: {
          title: "Posts Configuration",
        },
        path: "/configs/postspages",
        name: "config-postspages",
        component: () => import("@/views/configs/PostsConfig.vue"),
      },
      // {
      //   meta: {
      //     title: "QuickBooks Configuration",
      //   },
      //   path: "/configs/quickbooks",
      //   name: "config-quickbooks",
      //   component: () => import("@/views/configs/QuickBooksConfig.vue"),
      // },
      {
        meta: {
          title: "CloudFlare Configuration",
        },
        path: "/configs/cloudflare",
        name: "config-cloudflare",
        component: () => import("@/views/configs/CloudFlareConfig.vue"),
      },
      {
        meta: {
          title: "Google API Configuration",
        },
        path: "/configs/google",
        name: "config-google",
        component: () => import("@/views/configs/GoogleConfig.vue"),
      },
      {
        meta: {
          title: "Payment API Configuration",
        },
        path: "/configs/paymentapi",
        name: "config-paymentapi",
        component: () => import("@/views/configs/PaymentConfig.vue"),
      },
      // {
      //   meta: {
      //     title: "Social Configuration",
      //   },
      //   path: "/configs/social",
      //   name: "config-social",
      //   component: () => import("@/views/configs/SocialConfig.vue"),
      // },
      {
        meta: {
          title: "SMTP Configuration",
        },
        path: "/configs/smtp",
        name: "config-smtp",
        component: () => import("@/views/configs/SMTPConfig.vue"),
      },
    ]
  },
  {
    meta: {
      title: "Emails",
    },
    path: "/emails",
    name: "emails",
    component: () => import("@/views/emails/EmailLayout.vue"),
    children: [
      {
        meta: {
          title: "Email Templates",
        },
        path: "/email/templates",
        name: "email-templates",
        component: () => import("@/views/emails/indexTemplates.vue"),
      },
      {
        meta: {
          title: "Email Templates",
        },
        path: "/email/templates/form/:id?",
        name: "email-templates-form",
        component: () => import("@/views/emails/FormEmailTemplate.vue"),
      },
      {
        meta: {
          title: "Email History",
        },
        path: "/email/history",
        name: "email-history",
        component: () => import("@/views/emails/indexHistory.vue"),
      },
    ]
  },
  {
    meta: {
      title: "User Detail",
    },
    path: "/users/:id",
    name: "user-detail",
    component: () => import("@/views/users/ProfileView.vue"),
  },
  {
    meta: {
      title: "Profile",
    },
    path: "/profile",
    name: "profile",
    component: () => import("@/views/ProfileView.vue"),
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
const modules = import.meta.globEager('./../views/**/router.js');

Object.keys(modules).forEach((key) => {
  const mod = modules[key].default || {};
  routes = [...routes, ...mod]
});
const router = createRouter({
  history: createWebHashHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || {top: 0};
  },
});
router.beforeEach(async (to) => {
  // redirect to login page if not logged in and trying to access a restricted page
  const auth = useAuthStore();

  if (!to.meta.isPublic && !auth.isLogin()) {
    auth.returnUrl = to.fullPath;
    return '/login';
  }
  useAppStateStore().setTitle(to.meta.title)
});
export default router;
