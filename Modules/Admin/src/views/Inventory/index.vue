<script setup>
  import router from "@/router";
  import SectionMain from "@/components/SectionMain.vue";
  import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
  import dayjs from "dayjs";
  import {h, watch, ref} from "vue";
  import {useAuthStore} from "@/stores/auth";
  import {DataTable} from "@/components";
  import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
  import {UseDataTable} from "@/utils/UseDataTable";
  import {mdiBallotOutline, mdiDelete, mdiEye} from "@mdi/js";
  import {BaseIcon} from "@/components";
  import Detail from "./Detail.vue";

  import {PlusOutlined, LoadingOutlined, DeleteOutlined, FormOutlined} from '@ant-design/icons-vue';

  const prefix = "inventory";
  const {fetchListApi} = UseEloquentRouter(prefix);
  const auth = useAuthStore();

  const visible = ref(false);

  const productDetail = ref({});

  const itemActions = [];
  const listActions = [
    {
      label: "Add",
      action: () => {
        visible.value = true;
      },
    },
  ];
  const columns = [
    {
      title: "Type",
      key: "type",
      width: 80
    },
    {
      title: "Product",
      key: "product",
    },
    {
      title: "Supplier",
      key: "supplier",
    },
    {
      title: "Trademark",
      key: "trademark",
    },
    {
      title: "Amount",
      key: "amount",
      width: 100,
      class: 'text-center'
    },
    {
      title: "Date",
      key: "date",
      width: 100,
    },
    {
      title: "Expiration Date",
      key: "expiration_date",
      width: 100,
    },
    {
      title: "Created By",
      key: "created_by",
      width: 100,
    },
    {
      title: "Created at",
      key: "created_at",
      width: 130,
    },
  ];

  const tableConfig = UseDataTable(fetchListApi, {
    columns,
    showSelection: false,
    showSort: false,
    addAction: {
      action: (reload) => {
        //showEditUser({}, reload)
        productDetail.value.id = 0;
        visible.value = true;
      },
      ifShow: auth.hasPermission('Inventory.create')
    },
    listActions: [],
    itemActions: [
      {
        ifShow: auth.hasPermission('Inventory.update'),
        label: "Edit",
        key: "edit",
        action: (item) => {
          productDetail.value = item;
          visible.value = true;
        },
      },
      {
        ifShow: auth.hasPermission('Inventory.delete'),
        label: '',
        class: 'font-medium text-red-600 dark:text-red-500 hover:underline',
        icon: mdiDelete,
        key: 'delete',
        action(item, reload) {
          Api.delete(prefix + '/' + item.id).then(rs => {
            notification[rs.data.code == 0 ? 'error' : 'success']({
              message: 'Notification',
              description: rs.data.message,
            });
          }).finally(() => {
            reload();
          });
        }
      }
    ],
    selectionActions: [

    ]
  });

  let reloadTable = () => {
  };

  watch(router.currentRoute, (currentRoute) => {
    if (currentRoute.path === "/" + prefix) {
      reloadTable();
    }
  });

  function registerTable({reload}) {
    reloadTable = reload;
  }

  const close = () => {
    visible.value = false;
    reloadTable();
  }

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <DataTable v-bind="tableConfig" @register="registerTable">
        <template #cellAction[delete]="{ item, actionMethod }">
          <a-popconfirm title="Do you want delete this?" ok-text="Yes" cancel-text="No" @confirm="actionMethod">
            <a-button type="text" danger :icon="h(DeleteOutlined)" label="" :outline="true">
            </a-button>
          </a-popconfirm>
        </template>
        <template #cellAction[edit]="{ item, actionMethod }">
          <a-button type="text" :icon="h(FormOutlined)" label="" :outline="true" @click="actionMethod">
          </a-button>
        </template>
        <template #cell[type]="{ item, column }">
          <a-tag class="capitalize" :color="item.type == 'in' ? 'green' : 'red'">{{item.type}}</a-tag>
        </template>
        <template #cell[product]="{ item, column }">
          <div class="flex flex-row items-center">
            <img class="w-20 h-auto float-left" :src="item.product.image_url" :alt="item.product.name" v-if="item.product.image_url"/>
            <img class="w-20 h-auto float-left" src="/src/assets/no_image_available.png" v-else/>
            <label class="ml-2 font-semibold">{{item.product.name}}</label>
          </div>
        </template>
        <template #cell[supplier]="{ item, column }">
          <div class="flex flex-row items-center">
            <img class="w-20 h-auto float-left" :src="item.supplier.image_url" :alt="item.supplier.name" v-if="item.supplier.image_url"/>
            <img class="w-20 h-auto float-left" src="/src/assets/no_image_available.png" v-else/>
            <label class="ml-2 font-semibold">{{item.supplier.name}}</label>
          </div>
        </template>
        <template #cell[trademark]="{ item, column }">
          <div class="flex flex-row items-center">
            <img class="w-20 h-auto float-left" :src="item.trademark.image_url" :alt="item.trademark.name" v-if="item.trademark.image_url"/>
            <img class="w-20 h-auto float-left" src="/src/assets/no_image_available.png" v-else/>
            <label class="ml-2 font-semibold">{{item.trademark.name}}</label>
          </div>
        </template>
        <template #cell[slug]="{ item, column }">
          /{{ item.slug }}
        </template>
        <template #cell[status]="{ item, column }">
          <a-tag v-if="item.status == 'A'" color="green">Active</a-tag>
          <a-tag v-if="item.status == 'D'" color="green">Inactive</a-tag>
        </template>
      </DataTable>
    </SectionMain>
  </LayoutAuthenticated>
  <a-drawer :closable="false" style="position:relative;display:flex;flex-direction:column;height:100vh;" @close="close" :open="visible" width="80vw" :maskClosable="false">
    <Detail :value="productDetail" @close="close" :key="productDetail.id"></Detail>
  </a-drawer>
</template>
