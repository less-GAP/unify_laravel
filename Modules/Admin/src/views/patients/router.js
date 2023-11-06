export default [{
  meta: {
    title: "Patient",
    permission: 'Patient.menu',
  },
  path: "/patient",
  name: "patient",
  component: () => import("./index.vue"),
  children: [
    //   {
    //     meta: {
    //       title: "Add Patient",
    //       permission: 'patient.create',
    //     },
    //     path: "/patient/new",
    //     name: "patient-add",
    //     component: () => import("./Edit.vue"),
    //   },
    //   {
    //     meta: {
    //       title: "Edit Patient",
    //       permission: 'patient.edit',
    //     },
    //     path: "/patient/:id/edit",
    //     name: "patient-edit",
    //     component: () => import("./Edit.vue"),
    //   },
    {
      meta: {
        title: "Approve Patient",
        permission: 'Patient.approveRunning',
      },
      path: "/patient/process/:id",
      name: "patient-process",
      component: () => import("./Process.vue"),
    },
    {
      meta: {
        title: "Patient Tasks",
        permission: 'Task.menu',
      },
      path: "/patient/task/:id",
      name: "patient-task",
      component: () => import("./Task.vue"),
    },
    {
      meta: {
        title: "Patient History",
        permission: 'Patient.history',
      },
      path: "/patient/history/:id",
      name: "patient-history",
      component: () => import("./History.vue"),
    },
  ]
},
  {
    meta: {
      title: "Patient Detail",
    },
    path: "/patient/edit/:id",
    name: "patient-detail",
    component: () => import("./Detail.vue"),
  },
  {
    meta: {
      title: "Patient View",
    },
    path: "/patient/view/:id",
    name: "patient-view",
    component: () => import("./View.vue"),
  },
]
