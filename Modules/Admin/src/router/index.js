import {createRouter, createWebHashHistory} from "vue-router";
import Style from "@/views/StyleView.vue";
import Home from "@/views/HomeView.vue";
import {useAuthStore} from "@/stores/auth";
import {useAppStateStore} from "@/stores/appState";

const publicPages = ['/login'];

const routes = [
  {
    meta: {
      title: "Dashboard",
    },
    path: "/",
    name: "dashboard",
    component: Home,
  },
  {
    meta: {
      title: "Kanban",
    },
    path: "/kanban",
    name: "kanban",
    component: () => import("@/views/kanban/index.vue"),
  },
  {
    meta: {
      title: "Inbox",
    },
    path: "/inbox",
    name: "inbox",
    component: () => import("@/views/inbox/index.vue"),
  },
  {
    meta: {
      title: "Visa Applications",
    },
    path: "/visa-applications",
    name: "visa-applications",
    component: () => import("@/views/visa-applications/index.vue"),
  },
  {
    meta: {
      title: "Visa Applications",
    },
    path: "/visa-applications/:id",
    name: "visa-applications-form",
    component: () => import("@/views/visa-applications/FormPage.vue"),
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
      title: "Websites",
    },
    path: "/websites",
    name: "websites",
    component: () => import("@/views/websites/index.vue"),
  },
  {
    meta: {
      title: "Website Detail",
    },
    path: "/websites/:id",
    name: "website-detail",
    component: () => import("@/views/websites/Detail.vue"),
  },
  {
    meta: {
      title: "Themes",
    },
    path: "/themes",
    name: "themes",
    component: () => import("@/views/themes/index.vue"),
  },
  {
    meta: {
      title: "Theme Detail",
    },
    path: "/themes/:id",
    name: "theme-detail",
    component: () => import("@/views/themes/Detail.vue"),
  },
  {
    meta: {
      title: "Data Source",
    },
    path: "/data-source",
    name: "data-source",
    component: () => import("@/views/data-source/index.vue"),
  },
  {
    meta: {
      title: "Data Source Detail",
    },
    path: "/data-source/:id",
    name: "data-source-detail",
    component: () => import("@/views/data-source/Detail.vue"),
  },
  {
    meta: {
      title: "Users",
    },
    path: "/users",
    name: "users",
    component: () => import("@/views/users/index.vue"),
  },
  {
    meta: {
      title: "Profile",
    },
    path: "/user/profile",
    name: "user-profile",
    component: () => import("@/views/users/ProfileView.vue"),
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
  {
    meta: {
      title: "Error",
    },
    path: "/error",
    name: "error",
    component: () => import("@/views/ErrorView.vue"),
  },
];

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
