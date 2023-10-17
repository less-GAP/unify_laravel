export default [
  {
    meta: {
      title: "Product",
      permission: "product.list",
    },
    path: "/product",
    name: "product",
    component: () => import("./index.vue"),
  },
];
