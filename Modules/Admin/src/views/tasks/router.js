export default [
  {
    meta: {
      title: "Task",
      permission: "task.list",
    },
    path: "/task",
    name: "task",

    component: () => import("./index.vue"),
  },
];
