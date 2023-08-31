export default [{
  meta: {
    title: "Master data",
  },
  path: "/master-data",
  name: "master-data",
  redirect: '/master-data/patient-status',
  component: () => import("@/views/master-data/Layout.vue"),
  children: [
    {
      meta: {
        title: "Patient Status",
      },
      path: "/master-data/patient-status",
      name: "patient-status",
      component: () => import("./patient-status/index.vue"),
    },

  ]
}]
