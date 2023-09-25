export default [
  {
    meta: {
      title: "Log",
      permission: "log.list",
    },
    path: "/logs",
    name: "log",
    component: () => import("./index.vue"),
  },
];
