export default [{
  meta: {
    title: "Master data",
  },
  path: "/master-data",
  permission:'Admin',
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
    {
      meta: {
        title: "Insurance Coverage",
      },
      path: "/master-data/insurance",
      name: "insurance",
      component: () => import("./insurance/index.vue"),
    },
    {
      meta: {
        title: "Permissions",
      },
      path: "/master-data/permissions",
      name: "permissions",
      component: () => import("./permissions/index.vue"),
      children:[
        {
          meta: {
            title: "Permissions",
          },
          path: "/master-data/permissions/:id",
          name: "permissions-detail",
          component: () => import("./permissions/form.vue"),
        }
      ]
    },
    {
      meta: {
        title: "Roles",
      },
      path: "/master-data/roles",
      name: "roles",
      component: () => import("./roles/index.vue"),
      children:[
        {
          meta: {
            title: "Roles",
          },
          path: "/master-data/roles/:id",
          name: "roles-detail",
          component: () => import("./roles/form.vue"),
        },{
          meta: {
            title: "Role Permissions",
          },
          path: "/master-data/roles/:id/permissions",
          name: "roles-permissions",
          component: () => import("./roles/permissions.vue"),
        }
      ]
    },
  ]
}]
