<template>
  <DataTable v-bind="tableConfig" @register="registerTable">
    <template #cellAction[edit]="{ item, actionMethod }">
      <a-button type="link" class="!underline" @click="selectProduct(item)">Select</a-button>
    </template>
    <template #cell[id]="{ item, column }">
      <div>{{ item.id }}</div>
    </template>
    <template #cell[image]="{ item, column }">
      <img class="w-20 h-auto float-left" :src="item.image_url" :alt="item.name" v-if="item.image_url"/>
      <img class="w-20 h-auto float-left" src="/src/assets/no_image_available.png" v-else/>
    </template>
    <template #cell[slug]="{ item, column }">
      /{{ item.slug }}
    </template>
    <template #cell[status]="{ item, column }">
      <a-tag v-if="item.status == 'A'" color="green">Active</a-tag>
      <a-tag v-if="item.status == 'D'" color="green">Inactive</a-tag>
    </template>
  </DataTable>

</template>

<script lang="ts">
  import {computed, defineComponent, watch, ref, onMounted, unref, toRaw, h} from 'vue';
  import {isArray, isFunction} from '@/utils/is';
  import {DragOutlined, DeleteOutlined, PlusOutlined, FormOutlined} from '@ant-design/icons-vue';


  import {DataTable} from "@/components";

  import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
  import {UseDataTable} from "@/utils/UseDataTable";

  import Api from '@/utils/Api';
  import {notification} from "ant-design-vue";
  import {mdiDelete} from "@mdi/js";

  const prefix = "product";
  const {fetchListApi} = UseEloquentRouter(prefix);

  const columns = [
    {
      title: "Image",
      key: "image",
      width: 100
    },
    {
      title: "Name",
      key: "name",
    },
    {
      title: "Slug",
      key: "slug",
    },
    {
      title: "Inventory",
      key: "inventory",
      class: 'text-center',
      width: 100
    },
    {
      title: "Status",
      key: "status",
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
    showSelection: true,
    showSort: false,
    showReload: true,
    addAction: {},
    listActions: [],
    itemActions: [
      {
        ifShow: true,
        label: "Edit",
        key: "edit",
      },

    ],
    selectionActions: []
  });

  let reloadTable = () => {
  };

  export default defineComponent({
    components: {
      DataTable,
      FormOutlined,
    },
    props: {
      value: Array,
    },
    emits: ['select', 'close'],
    setup(props, {emit}) {
      const isFirstLoaded = ref<Boolean>(false);
      const loading = ref(false);

      function back() {
        emit('close');
      }

      function selectProduct(item) {
        emit('select', item);
      };

      onMounted(() => {
        //console.log(props.value)
        reloadTable()
      });

      function registerTable({reload}) {
        reloadTable = reload;
      }


      return {
        loading,
        back,
        selectProduct,
        tableConfig,
        h,
        registerTable
      };
    },
  });
</script>

<style>
  .ant-input {
    border-color: #d9d9d9 !important;
    border-radius: 5px !important;
  }
</style>
