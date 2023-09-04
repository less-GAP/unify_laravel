export default [{
  meta: {
    title: "Patient",
  },
  path: "/patient",
  name: "patient",
  component: () => import("./index.vue"),
  children: [
    {
      meta: {
        title: "Add Patient",
      },
      path: "/patient/new",
      name: "patient-add",
      component: () => import("./Edit.vue"),
    },
    {
      meta: {
        title: "Edit Patient",
      },
      path: "/patient/:id/edit",
      name: "patient-edit",
      component: () => import("./Edit.vue"),
    },
    {
      meta: {
        title: "Approve Patient",
      },
      path: "/patient/:id/process",
      name: "patient-process",
      component: () => import("./Process.vue"),
    },
    {
      meta: {
        title: "Patient Tasks",
      },
      path: "/patient/:id/task",
      name: "patient-task",
      component: () => import("./Task.vue"),
    },
    {
      meta: {
        title: "Patient History",
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
  },
  path: "/patient/:id/detail",
  name: "patient-detail",
  component: () => import("./Detail.vue"),
},

]
