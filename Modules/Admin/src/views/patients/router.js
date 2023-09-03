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
        title: "Patient Edit",
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
  ]
},
{
  meta: {
    title: "Patient Detail",
  },
  path: "/patient/:id",
  name: "patient-detail",
  component: () => import("./Detail.vue"),
},

]
