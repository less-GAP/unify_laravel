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
  mdiCheckboxMarkedCircleAutoOutline,
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
    to: "/task",
    permission: 'task.list',
    icon: mdiCheckboxMarkedCircleAutoOutline,
    label: "Task",
  },
  {
    to: null,
    permission: 'inventory.list',
    label: "Inventory",
  },
  {
    to: "/delivery",
    permission: 'delivery.list',
    label: "Delivery",
    icon: mdiTruckDelivery,
    disable: true,
  },
  {
    to: "/warehouse",
    permission: 'warehouse.list',
    label: "Warehouse",
    icon: mdiStorefront,
    disable: true,
  },
  {
    to: "/product",
    permission: 'product.list',
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
    permission: 'email.list',
    label: "Email",
    icon: mdiMessageText,
    disable: true,
  },
  {
    to: "/sms",
    permission: 'sms.list',
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
    to: "/doctor",
    permission: "doctor.list",
    label: "Doctors",
    icon: mdiDoctor,
  },
  {
    to: "/points",
    permission: 'points.list',
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
    permission: 'report.list',
    icon: mdiChartAreasplineVariant,
    disable: true,
  },
  {
    to: "/logs",
    permission: 'logs.list',
    label: "Logs",
    icon: mdiClockTimeTwo,
  },
  {
    to: "/forms",
    permission: 'forms.list',
    label: "Forms",
    icon: mdiFileExport,
    disable: true,
  },
  {
    label: "Backup",
    permission: 'backup.list',
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
    permission: 'setting',
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
