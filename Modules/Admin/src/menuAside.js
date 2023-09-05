// https://pictogrammers.com/library/mdi/
import {
  mdiMonitor,
  mdiImageMultiple,
  mdiCog,
  mdiAccountMultiple,
  mdiPaletteSwatch,
  mdiDatabase,
  mdiCardAccountDetails
} from "@mdi/js";

export default [
  {
    to: "/dashboard",
    icon: mdiMonitor,
    label: "Dashboard",
  },
  {
    to: "/patient",
    icon: mdiCardAccountDetails,
    label: "Patient",
  },
  {
    to: "/user",
    label: "Users",
    icon: mdiAccountMultiple,
  },
  {
    label: "File Manager",
    icon: mdiImageMultiple,
    to: "/files",
  },
  {
    label: "Master Data",
    icon: mdiDatabase,
    to: "/master-data",
  },
  {
    label: "System Config",
    icon: mdiCog,
    to: "/configs/general",
  },
  {
    label: "Template",
    icon: mdiPaletteSwatch,
    to: "/email/templates",
    // menu: [
    //   {
    //     to: "/email/templates",
    //     label: "Email",
    //   } ,
    //   {
    //     to: "/invoice/templates",
    //     label: "Invoice",
    //   }
    // ],
  }
];
