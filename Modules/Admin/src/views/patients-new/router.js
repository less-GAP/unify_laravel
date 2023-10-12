export default [
  {
    meta: {
      title: "Patient",
      permission: 'patient.list',
    },
    path: "/patient-new",
    name: "patient-new",
    component: () => import("./index.vue"),
    children: [
      {
        meta: {
          title: "Add Patient",
          permission: 'patient.create',
        },
        path: "/patient/new",
        name: "patient-add",
        component: () => import("./Edit.vue"),
      },
      {
        meta: {
          title: "Edit Patient",
          permission: 'patient.edit',
        },
        path: "/patient/:id/edit",
        name: "patient-edit",
        component: () => import("./Edit.vue"),
      },
      {
        meta: {
          title: "Approve Patient",
          permission: 'patient.approve',
        },
        path: "/patient/:id/process",
        name: "patient-process",
        component: () => import("./Process.vue"),
      },
      {
        meta: {
          title: "Patient Tasks",
          permission: 'task.list',
        },
        path: "/patient/:id/task",
        name: "patient-task",
        component: () => import("./Task.vue"),
      },
      {
        meta: {
          title: "Patient History",
          permission: 'patient.history',
        },
        path: "/patient/:id/history",
        name: "patient-history",
        component: () => import("./History.vue"),
      },
    ]
  },
  {
    meta: {
      title: "Patient Detail",
      permission: 'patient.view',
    },
    path: "/patient/:id/detail",
    name: "patient-detail",
    component: () => import("./Detail.vue"),
  },

]
