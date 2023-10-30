<script setup>
  import router from "@/router";
  import SectionMain from "@/components/SectionMain.vue";
  import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
  import dayjs from "dayjs";
  import {h, watch, ref, toRaw} from "vue";
  import {useAuthStore} from "@/stores/auth";
  import {DataTable} from "@/components";
  import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
  import {UseDataTable} from "@/utils/UseDataTable";
  import {mdiBallotOutline, mdiDelete, mdiEye} from "@mdi/js";
  import {BaseIcon} from "@/components";
  import Detail from "./Detail.vue";

  import Shipper from "./components/Shipper.vue";

  import {PlusOutlined, LoadingOutlined, DeleteOutlined, FormOutlined, EyeOutlined, PlusSquareOutlined} from '@ant-design/icons-vue';

  import Api from "@/utils/Api";

  const prefix = "order";
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
      title: 'Order ID',
      dataIndex: 'id',
      key: 'id',
      width: 50,
      render: (id, record, index) => {
        ++index;
        return index;
      },
      class: 'text-center'
    },
    {
      title: "Patient",
      key: "patient_id",
    },
    {
      title: "Shipping",
      key: "shipping_id",
      width: 200,
      class: 'text-left'
    },
    {
      title: "Delivery Date",
      key: "delivery_date",
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
    {
      title: "Status",
      key: "status",
      width: 130,
    },
  ];

  const tableConfig = UseDataTable(fetchListApi, {
    columns,
    showSelection: false,
    showSort: false,
    showReload: true,
    addAction: {
      action: (reload) => {
        //showEditUser({}, reload)
        router.push(prefix + '/0');
      },
      ifShow: auth.hasPermission('Order.create')
    },
    listActions: [],
    itemActions: [
      {
        ifShow: auth.hasPermission('Order.menu'),
        label: "View",
        key: "view",
        action: (item) => {
          router.push(prefix + '/view/' + item.id);
        },
      },
      {
        ifShow: auth.hasPermission('Order.update'),
        label: "Edit",
        key: "edit",
        action: (item) => {
          router.push(prefix + '/' + item.id);
        },
      },

      {
        ifShow: auth.hasPermission('Order.assign'),
        label: "Assign",
        key: "assign",
        action: (item) => {
          var shipper = {
            order_id: item.id,
            shipping_id: item.shipping_id,
            shipper_phone: item.shipper_phone,
            shipper_email: item.shipper_email,
          };
          editShipper(shipper);
        },
      },
      {
        ifShow: auth.hasPermission('Order.delete'),
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
    selectionActions: []
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


  const showShipper = ref(false);
  const selectShipper = ref({});
  const onSelectShipper = (item) => {
    Api.post(prefix + '/assign', toRaw(item)).then(rs => {
      // notification[rs.data.code == 0 ? 'error' : 'success']({
      //   message: 'Notification',
      //   description: rs.data.message,
      // });
      if (rs.data.code == 1) {
        showShipper.value = false;
        reloadTable();
      }
    });
  }

  const editShipper = (item) => {
    selectShipper.value = item;
    showShipper.value = true;
  };


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
        <template #cellAction[view]="{ item, actionMethod }">
          <a-button type="text" :icon="h(EyeOutlined)" label="" :outline="true" @click="actionMethod">
          </a-button>
        </template>

        <template #cellAction[assign]="{ item, actionMethod }">
          <a-button type="text" :icon="h(PlusSquareOutlined)" label="" :outline="true" @click="actionMethod">
          </a-button>
        </template>


        <template #cell[type]="{ item, column }">
          <a-tag class="capitalize" :color="item.type == 'in' ? 'green' : 'red'">{{item.type}}</a-tag>
        </template>
        <template #cell[patient_id]="{ item, column }">
          <div v-if="item.patient">
            <span><b>#{{item.patient.unify_number}} - {{item.patient.full_name}}</b></span><br/>
            <span v-if="item.phone">Tel: {{item.phone}}</span><br v-if="item.phone"/>
            <span v-if="item.email">{{item.email}}</span><br v-if="item.email"/>
            <span v-if="item.street">{{item.street}}, {{item.city}}, {{item.state}}, {{item.zip}}</span>
          </div>
        </template>
        <template #cell[shipping_id]="{ item, column }">
          <div v-if="item.shipping_id">
            <span><b>{{item.shipper_name}}</b></span><br/>
            <span v-if="item.shipper_phone">Tel: {{item.shipper_phone}}</span><br v-if="item.shipper_phone"/>
            <span v-if="item.shipper_email">{{item.shipper_email}}</span>
          </div>
        </template>

        <template #cell[delivery_date]="{ item, column }">
          {{dayjs(item.delivery_date, "YYYY-MM-DD").format("MM-DD-YYYY" )}}
        </template>

        <template #cell[status]="{ item, column }">
          <a-tag v-if="item.status == 'new'" color="#2db7f5">New</a-tag>
          <a-tag v-if="item.status == 'confirmed'" color="#108ee9">Confirmed</a-tag>
          <a-tag v-if="item.status == 'delivering'" color="#87d068">Delivering</a-tag>
          <a-tag v-if="item.status == 'delivered'" color="#green">Delivered</a-tag>
          <a-tag v-if="item.status == 'done'" color="#green">Done</a-tag>
        </template>
      </DataTable>
    </SectionMain>
  </LayoutAuthenticated>
  <a-drawer :closable="false" style="position:relative;display:flex;flex-direction:column;height:100vh;" @close="close" :open="visible" width="80vw" :maskClosable="false">
    <Detail :value="productDetail" @close="close" :key="productDetail.id"></Detail>
  </a-drawer>

  <a-modal append-to-body v-model:open="showShipper" :zIndex="10" width="40%" title="Select Shipper" :closable="true" :footer="null" :maskClosable="false">
    <Shipper :value="selectShipper" @close="showShipper = false" @select="onSelectShipper" :key="selectShipper.id ? selectShipper : 0"></Shipper>
  </a-modal>
</template>
