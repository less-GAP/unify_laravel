export default [
  {
    meta: {
      title: "Inventory",
      permission: "Inventory.list",
    },
    path: "/inventory",
    name: "inventory",
    component: () => import("./index.vue"),
  },
  {
    meta: {
      title: "Inventory Detail",
      permission: "Inventory.update",
    },
    path: "/inventory/:id",
    name: "inventory-detail",
    component: () => import("./Detail.vue"),
  },
];
