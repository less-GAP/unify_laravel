export default [
  {
    meta: {
      title: "Configs",
    },
    path: "/configs",
    name: "configs",
    component: () => import("@/views/configs/ConfigLayout.vue"),
    children: [{
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
      {
        meta: {
          title: "QuickBooks Configuration",
        },
        path: "/configs/quickbooks",
        name: "config-quickbooks",
        component: () => import("@/views/configs/QuickBooksConfig.vue"),
      },
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
          title: "Marketing Configuration",
        },
        path: "/configs/marketing",
        name: "config-marketing",
        component: () => import("@/views/configs/MarketingConfig.vue"),
      },
      {
        meta: {
          title: "Payment API Configuration",
        },
        path: "/configs/paymentapi",
        name: "config-paymentapi",
        component: () => import("@/views/configs/PaymentConfig.vue"),
      },
      {
        meta: {
          title: "Social Configuration",
        },
        path: "/configs/social",
        name: "config-social",
        component: () => import("@/views/configs/SocialConfig.vue"),
      },
      {
        meta: {
          title: "SMTP Configuration",
        },
        path: "/configs/smtp",
        name: "config-smtp",
        component: () => import("@/views/configs/SMTPConfig.vue"),
      },
      {
        meta: {
          title: "System Events",
        },
        path: "/configs/event-setting",
        name: "system-events",
        component: () => import("./system-events/index.vue"),
        children:[
          {
            meta: {
              title: "System Events",
            },
            path: "/configs/event-setting/:id",
            name: "system-events",
            component: () => import("./system-events/form.vue"),
          },
        ]
      },
    ]
  }
]
