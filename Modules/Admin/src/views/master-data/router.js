export default [{
  meta: {
    title: "Master data",
  },
  path: "/master-data",
  name: "master-data",
  redirect: '/master-data/task-status',
  component: () => import("@/views/master-data/Layout.vue"),
  children: [
    {
      meta: {
        title: "Task Status",
      },
      path: "/master-data/task-status",
      name: "task-status",
      component: () => import("./task-status/index.vue"),
    },

  ]
}]
