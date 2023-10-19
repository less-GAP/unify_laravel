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
  mdiTooltipQuestion,
  mdiStoreSettingsOutline,
  mdiTrademark
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
    permission: 'Patient.list',
    icon: mdiCardAccountDetails,
    label: "Patient",
  },
  {
    to: "/task",
    permission: 'Task.list',
    icon: mdiCheckboxMarkedCircleAutoOutline,
    label: "Task",
  },
  {
    to: null,
    permission: 'Inventory.list',
    label: "Inventory",
  },
  {
    to: "/delivery",
    permission: 'Delivery.list',
    label: "Delivery",
    icon: mdiTruckDelivery,
    disable: true,
  },
  {
    to: "/inventory",
    permission: 'Inventory.list',
    label: "Inventory",
    icon: mdiStorefront,
    disable: false,
  },
  {
    to: "/product",
    permission: 'Product.list',
    label: "Product",
    icon: mdiGift,
    disable: false,
  },
  {
    to: "/supplier",
    permission: 'Supplier.list',
    label: "Supplier",
    icon: mdiStoreSettingsOutline,
    disable: false,
  },
  {
    to: "/trademark",
    permission: 'Trademark.list',
    label: "Trademark",
    icon: mdiTrademark,
    disable: false,
  },
  {
    to: null,
    label: "Marketing",
  },
  {
    to: "/email",
    permission: 'Email.list',
    label: "Email",
    icon: mdiMessageText,
  },
  {
    to: "/sms",
    permission: 'Sms.list',
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
    permission: 'User.list',
    label: "Users",
    icon: mdiAccountMultiple,
  },
  {
    label: "Customers",
    icon: mdiTooltipAccount,
    permission: 'Customers.list',
    menu: [{
      to: "/customers",
      label: "Customers list"
    },
      {
        to: "/customer-group",
        label: "Customer group",
      },
    ],
    disable: true,
  },
  {
    to: "/doctor",
    permission: "Doctor.list",
    label: "Doctors",
    icon: mdiDoctor,
  },
  {
    to: "/points",
    permission: 'Points.list',
    label: "Point",
    icon: mdiStarCircle,
    disable: true,
  },
  {
    permission: 'File.list',
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
    permission: 'Report.list',
    icon: mdiChartAreasplineVariant,
    disable: true,
  },
  {
    to: "/logs",
    permission: 'Logs.list',
    label: "Logs",
    icon: mdiClockTimeTwo,
  },
  {
    to: "/forms",
    permission: 'Forms.list',
    label: "Forms",
    icon: mdiFileExport,
    disable: true,
  },
  {
    label: "Backup",
    permission: 'Backup.list',
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
    permission: 'Setting',
    label: "Platform",
  },
  {
    permission: 'MasterData.list',
    label: "Master Data",
    icon: mdiDatabase,
    to: "/master-data",
  },
  {
    permission: 'Config.list',
    label: "System Config",
    icon: mdiCog,
    to: "/configs/general",
  },
  {
    permission: 'Template.list',
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
