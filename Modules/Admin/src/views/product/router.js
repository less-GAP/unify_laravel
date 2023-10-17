export default [
  {
    meta: {
      title: "Product",
      permission: "Product.list",
    },
    path: "/product",
    name: "product",
    component: () => import("./index.vue"),
  },
];
