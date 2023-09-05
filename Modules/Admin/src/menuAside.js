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
    permission:'patient.list',
    icon: mdiCardAccountDetails,
    label: "Patient",
  },
  {
    to: "/user",
    permission:'user.list',
    label: "Users",
    icon: mdiAccountMultiple,
  },
  {
    permission:'file.list',
    label: "File Manager",
    icon: mdiImageMultiple,
    to: "/files",
  },
  {
    permission:'masterData.list',
    label: "Master Data",
    icon: mdiDatabase,
    to: "/master-data",
  },
  {
    permission:'config.list',
    label: "System Config",
    icon: mdiCog,
    to: "/configs/general",
  },
  {
    permission:'template.list',
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
