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
        title: "Patient Detail",
      },
      path: "/patient/:id",
      name: "patient-detail",
      component: () => import("./Detail.vue"),
    },
    {
      meta: {
        title: "Patient Process",
      },
      path: "/patient/:id/process",
      name: "patient-process",
      component: () => import("./Process.vue"),
    }
  ]
}

]
