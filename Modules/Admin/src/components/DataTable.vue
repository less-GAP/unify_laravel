<script setup>
import { computed, ref, h, toRaw } from "vue";
import { useMainStore } from "@/stores/main";
import { mdiEye, mdiTrashCan } from "@mdi/js";
import { Button, Input, InputUpload } from "@/components/index";
import BaseIcon from "@/components/BaseIcon.vue";
import { DownOutlined, ReloadOutlined, SearchOutlined } from "@ant-design/icons-vue";

const emit = defineEmits(["register"]);

const props = defineProps({
  tableConfig: {
    type: Object,
    default: {}
  },
  pagination: {
    type: Object,
    default: {
      page: 1,
      total: 0,
      perPage: 20
    }
  },
  showSizeChanger: {
    type: Boolean,
    default: true
  },
  showReload: {
    type: Boolean,
    default: true
  },
  showSelection: {
    type: Boolean,
    default: true
  },
  showSort: {
    type: [Boolean, Array],
    default: false
  },
  params: {
    type: Object,
    default: {}
  },
  sort: {
    type: String,
    default: '-id'
  },
  filter: {
    type: Object,
    default: {}
  },
  columns: Array,
  selectionActions: {
    type: Array,
    default: []
  },
  itemActions: {
    type: Array,
    default: []
  },
  listActions: {
    type: Array,
    default: []
  },
  api: Function,
  addAction: Function,
});
const tableConfig = {
  item_key: 'id'
  , rowSelect: true
  , globalSearch: true
  , ...props.tableConfig
}
const tableData = ref({})
const filter = ref({
  search: '',
  ...props.filter
})
const orderBy = ref(props.sort)

function reset() {
  props.pagination.page = 1
  reload()
}

const tableColumns = computed(() => {
  const result = props.columns.map(item => {
    return {
      dataIndex: item.dataIndex ? item.dataIndex : item.key,
      ...item
    }
  })
  if (props.actionColumn && props.itemActions.length) {
    result.push({
      title: 'Actions',
      key: 'action',
      dataIndex: 'action'
    })
  }
  return result;
})

function getFilter() {
  let rs = {}
  for (let filterKey in filter.value) {
    rs['filter[' + filterKey + ']'] = filter.value[filterKey]
  }
  return rs
}

function reload() {
  if (props.api) {
    loading.value = true
    props.api({
      perPage: props.pagination.perPage,
      sort: orderBy.value,
      page: props.pagination.page, ...props.params,
      ...getFilter()
    }).then(rs => {
      tableData.value = rs.data
      props.pagination.total = rs.data?.total ? rs.data.total : 0
    }).finally(() => {
      checkAll.value = false
      loading.value = false
    })
  }
}

emit('register', { reload, filter })
const loading = ref(false);
const checkAll = ref(false);
const selectedKeys = ref([])
const selectedItems = ref([])

async function doSelectionAction(action) {
  const selectedKeys = selectedItems.value.map(item => item[tableConfig.item_key])
  await (action.action(selectedKeys, selectedItems.value))
  if (action.complete) {
    action.complete()
  }
  selectedItems.value = []
  reload()
}

function toggleCheckAll() {
  if (checkAll.value) {
    selectedItems.value = toRaw(tableData?.value.data || [])
  } else {
    selectedItems.value = []
  }
}

const rowSelection = computed(() => {
  if (!props.selectionActions || !props.selectionActions.length) {
    return false;
  }
  return {
    onChange: (selectedRowKeys, selectedRows) => {
      console.log(`selectedRowKeys: ${selectedRowKeys}`, 'selectedRows: ', selectedRows);
    },
    getCheckboxProps: (record) => ({
      disabled: record.name === 'Disabled User', // Column configuration not to be checked
      name: record.name,
    }),
  };
})
reload()
</script>

<template>
  <div class="flex flex-col sm:rounded-lg" id="table-list">
    <slot name="header" v-bind="{ tableConfig, filter, reload }">
    </slot>

    <div :loading="loading" class="flex items-center justify-between p-2 bg-white border border-gray-200 rounded-xl">

      <a-space>

        <a-input-search v-if="tableConfig.globalSearch" allow-clear @search="reload" @keyup.enter="reload"
          style="max-width: 300px" v-model:value="filter.search" placeholder="Enter to search..." :loading="loading" />
        <slot name="filter" v-bind="{ tableConfig, filter, reload }"></slot>
        <slot name="sort" v-bind="{ tableConfig, sort, filter, reload }">
          <a-select v-if="showSort" style="width: 140px" placeholder="Order by..." v-model:value="orderBy"
            @change="reload">
            <a-select-option v-for="sort in showSort" :key="sort.value" :value="sort.value">{{ sort.label }}
            </a-select-option>
          </a-select>
        </slot>

      </a-space>
      <span></span>

      <a-space>
        <a-button v-if="showReload">
          <template #icon>
            <reload-outlined @click="reload" />
          </template>
        </a-button>
        <a-button v-for="listAction in listActions" type="primary" @click="() => { listAction.action(reload) }">{{ listAction.label }}</a-button>
      </a-space>
    </div>
    <div class="flex-1 w-full my-5 overflow-auto bg-white rounded-lg shadow border-gray-200 border scroll-smooth">
      <a-skeleton active class="p-10" v-if="loading || !tableData.data" />

      <slot v-else name="table" v-bind="{ tableConfig, tableData, columns, selectionActions, reload }">
        <table class="w-full text-xs table-auto">
          <thead class="font-semibold text-gray-400 uppercase bg-gray-50">
            <tr>
              <th v-if="showSelection" scope="col" class="text-center px-1 py-2 w-3 whitespace-nowrap">
                <label class="w-full font-medium text-gray-900"> <input
                    @change="toggleCheckAll" :value="true" v-model="checkAll" type="checkbox"
                    class="rounded w-4 h-4 text-blue-600 focus:ring-blue-500">
                </label>
              </th>

              <th v-for="column in columns" scope="col" :width="column.width ? column.width : 'auto'"
                class="px-1 py-2 whitespace-nowrap">
                <div class="font-semibold text-left">
                  {{ __(column.title) }}
                </div>
              </th>

              <th v-if="itemActions.length" width="70" scope="col" class="px-1 py-2 whitespace-nowrap">
                {{ __('Action') }}
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="(item, index) in tableData.data" :key="item[tableConfig.item_key]"
              v-bind:class="{ 'border-b': (index % 2 === 0) }">
              <td v-if="showSelection" class="text-center p-1 w-3 whitespace-nowrap">
                <label :for="'checkbox-table-search-' + item[tableConfig.item_key]"
                  class="w-full font-medium text-gray-900"> <input
                    v-model="selectedItems" :id="item[tableConfig.item_key]" :value="item" type="checkbox"
                    class="rounded w-4 h-4 text-blue-600 focus:ring-blue-500">
                </label>
              </td>

              <td :data-label="column.title" v-for="column in columns"
                :class="'p-1 ' + (column.class ? column.class : '')">
                <template v-if="item.render">
                  {{ item.render() }}
                </template>
                <slot v-else :name="'cell[' + column.key + ']'" v-bind="{ item, column, index }">
                  {{ $style['format'][column.key] ? $style['format'][column.key](item[column.key]) : item[column.key] }}
                </slot>

              </td>
              <td v-if="itemActions.length" class="p-1">
                <div class="flex flex-wrap justify-center whitespace-nowrap">
                  <template v-for="itemAction in itemActions">
                    <slot :name="'cellAction[' + itemAction.key + ']'"
                      v-bind="{ item, itemAction, actionMethod() { itemAction.action(item, reload) } }">
                      <a-button @click="itemAction.action(item, reload)"
                        :class="itemAction.class || 'font-medium text-blue-600 dark:text-blue-500 hover:underline'"
                        type="link">
                        {{ itemAction.label }}
                      </a-button>
                    </slot>
                  </template>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </slot>
      <a-empty class="my-10" :description="false" v-if="tableData.data?.length === 0 && pagination.total === 0" />
    </div>

    <a-pagination style="height:40px" class="pt-2" v-if="tableData.data && pagination?.total"
      :showSizeChanger="showSizeChanger" @change="reload" v-model:current="pagination.page"
      v-model:pageSize="pagination.perPage" :total="pagination.total">
      <template #itemRender="{ type, originalElement }">
        <a v-if="type === 'prev'">Previous</a>
        <a v-else-if="type === 'next'">Next</a>
        <component :is="originalElement" v-else></component>
      </template>
    </a-pagination>
  </div>
</template>
<style scoped>
.item-actions> :not(:first-child) {
  margin-left: 10px
}
</style>
