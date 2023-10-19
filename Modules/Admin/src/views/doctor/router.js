export default [
  {
    meta: {
      title: "Doctor",
    },
    path: "/doctor",
    name: "doctor",
    component: () => import("./index.vue"),
    // children: [
    //   {
    //     meta: {
    //       title: "Add Doctor",
    //       permission: "doctor.create",
    //     },
    //     path: "/doctor/new",
    //     name: "doctor-add",
    //     component: () => import("./Edit.vue"),
    //   },
    //   {
    //     meta: {
    //       title: "Edit Doctor",
    //       permission: "doctor.edit",
    //     },
    //     path: "/doctor/:id/edit",
    //     name: "doctor-edit",
    //     component: () => import("./Edit.vue"),
    //   },
    // ],
  },
  {
    meta: {
      title: "Doctor Detail",
    },
    path: "/doctor/:id",
    name: "doctor-detail",
    component: () => import("./Detail.vue"),
  },
];
