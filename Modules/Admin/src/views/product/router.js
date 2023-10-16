export default [
  {
    meta: {
      title: "Product",
      permission: "product.list",
    },
    path: "/product",
    name: "product",
    component: () => import("./index.vue"),
    children: [
      {
        meta: {
          title: "Edit Product",
          permission: "product.update",
        },
        path: "/product/:id",
        name: "product-edit",
        component: () => import("./Edit.vue"),
      },
    ],
  },
];
