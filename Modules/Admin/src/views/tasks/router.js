export default [
  {
    meta: {
      title: "Task",
      permission: "task.list",
    },
    path: "/task",
    name: "task",

    component: () => import("./index.vue"),
    children: [
      {
        meta: {
          title: "Add Task",
          permission:'task.assign',
        },
        path: "/task/new",
        name: "task-add",
        component: () => import("./Edit.vue"),
      },
    ],
  },
];
