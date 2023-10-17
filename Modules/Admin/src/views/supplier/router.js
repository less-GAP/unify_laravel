export default [
  {
    meta: {
      title: "Supplier",
      permission: "Supplier.list",
    },
    path: "/supplier",
    name: "supplier",
    component: () => import("./index.vue"),
  },
];
