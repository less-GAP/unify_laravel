export default [
  {
    meta: {
      title: "Users",
    },
    path: "/user",
    name: "users",
    component: () => import("./index.vue"),
  },
  {
    meta: {
      title: "Users Detail",
    },
    path: "/user/:id",
    name: "user-detail",
    component: () => import("./Detail.vue"),
  }
]
