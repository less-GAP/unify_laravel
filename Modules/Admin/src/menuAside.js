// https://pictogrammers.com/library/mdi/
import {
  mdiMonitor,
  mdiImageMultiple,
  mdiCog,
  mdiAccountMultiple,
  mdiPaletteSwatch,
  mdiDatabase,
  mdiCardAccountDetails,
  mdiMessageText,
  mdiEmail,
  mdiTooltipAccount,
  mdiTruckDelivery,
  mdiGift,
  mdiStorefront,
  mdiCloudDownload,
  mdiChartAreasplineVariant,
  mdiFileExport,
  mdiDoctor,
  mdiClockTimeTwo,
  mdiStarCircle,
  mdiTooltipQuestion
} from "@mdi/js";

export default [{
    to: "/dashboard",
    icon: mdiMonitor,
    label: "Dashboard",
  },
  {
    to: null,
    label: "Patient",
  },
  {
    to: "/patient",
    permission: 'patient.list',
    icon: mdiCardAccountDetails,
    label: "Patient",
  },
  {
    to: null,
    label: "Inventory",
  },
  {
    to: "/delivery",
    label: "Delivery",
    icon: mdiTruckDelivery,
    disable: true,
  },
  {
    to: "/warehouse",
    label: "Warehouse",
    icon: mdiStorefront,
    disable: true,
  },
  {
    to: "/product",
    label: "Product",
    icon: mdiGift,
    disable: true,
  },

  {
    to: null,
    label: "Marketing",
  },
  {
    to: "/email",
    label: "Email",
    icon: mdiMessageText,
    disable: true,
  },
  {
    to: "/sms",
    label: "SMS",
    icon: mdiEmail,
    disable: true,
  },
  {
    to: null,
    label: "Other",
  },
  {
    to: "/user",
    permission: 'user.list',
    label: "Users",
    icon: mdiAccountMultiple,
  },
  {
    label: "Customers",
    icon: mdiTooltipAccount,
    menu: [{
        to: "/customers",
        label: "Customers list"
      },
      {
        to: "/customer-group",
        label: "Customer group",
      },
    ],
  },
  {
    to: "/doctors",
    label: "Doctors",
    icon: mdiDoctor,
    disable: true,
  },
  {
    to: "/points",
    label: "Point",
    icon: mdiStarCircle,
    disable: true,
  },
  {
    permission: 'file.list',
    label: "File Manager",
    icon: mdiImageMultiple,
    to: "/files",
  },
  {
    to: null,
    label: "System",
  },
  {
    to: "/report",
    label: "Report",
    icon: mdiChartAreasplineVariant,
    disable: true,
  },
  {
    to: "/logs",
    label: "Logs",
    icon: mdiClockTimeTwo,
    disable: true,
  },
  {
    to: "/forms",
    label: "Forms",
    icon: mdiFileExport,
    disable: true,
  },
  {
    label: "Backup",
    icon: mdiCloudDownload,
    to: "/backup",
    disable: true,
  },
  {
    label: "FAQs",
    icon: mdiTooltipQuestion,
    to: "/faqs",
  },
  {
    to: null,
    label: "Platform",
  },
  {
    permission: 'masterData.list',
    label: "Master Data",
    icon: mdiDatabase,
    to: "/master-data",
  },
  {
    permission: 'config.list',
    label: "System Config",
    icon: mdiCog,
    to: "/configs/general",
  },
  {
    permission: 'template.list',
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
