export default [
  {
    meta: {
      title: "Order",
      permission: "Order.menu",
    },
    path: "/order",
    name: "order",
    component: () => import("./index.vue"),
  },
  {
    meta: {
      title: "Order Detail",
      permission: "Order.update",
    },
    path: "/order/:id",
    name: "order-detail",
    component: () => import("./Detail.vue"),
  },
  {
    meta: {
      title: "Order Detail",
      permission: "Order.update",
    },
    path: "/order/view/:id",
    name: "order-view",
    component: () => import("./View.vue"),
  },
];
