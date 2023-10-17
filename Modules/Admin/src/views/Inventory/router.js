export default [
  {
    meta: {
      title: "inventory",
      permission: "Inventory.list",
    },
    path: "/inventory",
    name: "inventory",
    component: () => import("./index.vue"),
  },
];
