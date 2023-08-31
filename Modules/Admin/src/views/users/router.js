export default [
  {
    meta: {
      title: "Users",
    },
    path: "/users",
    name: "users",
    component: () => import("@/views/users/index.vue"),
  },

  {
    meta: {
      title: "Profile",
    },
    path: "/user/profile",
    name: "user-profile",
    component: () => import("@/views/users/ProfileView.vue"),
  }]
