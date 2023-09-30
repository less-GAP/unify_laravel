<script setup>
import {ReloadOutlined} from "@ant-design/icons-vue";
import {ref, toRaw, watch} from "vue";
import {useAppStateStore} from "@/stores/appState";

const appState = useAppStateStore();
const emit = defineEmits(["register"]);

const props = defineProps({
  tableConfig: {
    type: Object,
    default: {},
  },
  pagination: {
    type: Object,
    default: {
      page: 1,
      total: 0,
      perPage: 20,
    },
  },
  showSizeChanger: {
    type: Boolean,
    default: true,
  },
  versionKey: {
    type: String,
    default: null,
  },
  showReload: {
    type: Boolean,
    default: false,
  },
  showSelection: {
    type: Boolean,
    default: true,
  },
  globalSearch: {
    type: Boolean,
    default: true,
  },
  showSort: {
    type: [Boolean, Array],
    default: false,
  },
  showPagination: {
    type: [Boolean],
    default: true,
  },
  params: {
    type: Object,
    default: {},
  },
  sort: {
    type: String,
    default: "-id",
  },
  filter: {
    type: Object,
    default: {},
  },
  columns: Array,
  selectionActions: {
    type: Array,
    default: [],
  },
  itemActions: {
    type: Array,
    default: [],
  },
  listActions: {
    type: Array,
    default: [],
  },
  api: Function,
  addAction: Function,
});
const tableConfig = {
  item_key: "id",
  rowSelect: true,
  globalSearch: true,
  ...props.tableConfig,
};

const tableData = ref({});
const data = ref(null);
const filter = ref({
  search: "",
  ...props.filter,
});
const orderBy = ref(props.sort);

function getFilter() {
  let rs = {};
  for (let filterKey in filter.value) {
    rs["filter[" + filterKey + "]"] = filter.value[filterKey];
  }
  return rs;
}

function reload(showLoading = false) {
  if (props.api) {
    if (showLoading) {
      loading.value = true;
    }
    props
      .api({
        perPage: props.pagination.perPage,
        sort: orderBy.value,
        page: props.pagination.page,
        ...props.params,
        ...getFilter(),
      })
      .then((rs) => {
        if (rs.data?.total) {
          tableData.value = rs.data;
          data.value = rs.data.data;
          props.pagination.total = rs.data?.total ? rs.data.total : 0;
        } else {
          data.value = rs.data;
        }
      })
      .finally(() => {
        checkAll.value = false;
        if (showLoading) {
          loading.value = false;
        }
      });
  }
}

emit("register", {reload, filter});
const loading = ref(false);
const checkAll = ref(false);
const selectedItems = ref([]);

function toggleCheckAll() {
  if (checkAll.value) {
    selectedItems.value = toRaw(data.value || []);
  } else {
    selectedItems.value = [];
  }
}

watch(() => appState.versions[props.versionKey], () => {
  reload()
})
reload(true);
</script>

<template>
  <div id="table-list" class="flex flex-col sm:rounded-lg">
    <slot name="header" v-bind="{ tableConfig, filter, reload }"></slot>

    <div :loading="loading" class="flex items-center p-2 bg-white rounded-xl empty:hidden">
      <a-space>
        <a-input-search v-if="globalSearch" v-model:value="filter.search" allow-clear style="max-width: 300px"
                        placeholder="Enter to search..." :loading="loading" @search="reload" @keyup.enter="reload"/>
        <a-button v-if="showReload">
          <template #icon>
            <reload-outlined @click="reload(true)"/>
          </template>
        </a-button>
        <slot name="filter" v-bind="{ tableConfig, filter, reload }"></slot>
        <slot name="sort" v-bind="{ tableConfig, sort, filter, reload }">
          <a-select v-if="showSort" v-model:value="orderBy" style="width: 140px" placeholder="Order by..."
                    @change="reload">
            <a-select-option v-for="sort in showSort" :key="sort.value" :value="sort.value">{{ sort.label }}
            </a-select-option>
          </a-select>
        </slot>
      </a-space>
      <a-space class="!ml-auto">
        <a-button v-for="listAction in listActions" type="primary" @click="() => {
            listAction.action(reload);
          }
          ">{{ listAction.label }}
        </a-button>
        <slot name="action" v-bind="{ selectedItems, data, reload }"></slot>
      </a-space>
    </div>
    <div class="flex-1 w-full my-2 overflow-auto bg-white rounded-lg shadow scroll-smooth">
      <a-skeleton v-if="loading" active class="p-10"/>
      <slot v-else name="table" v-bind="{
        tableConfig,
        tableData,
        data,
        columns,
        selectionActions,
        reload,
      }">
        <table v-if="data?.data?.length !== 0" class="w-full table-auto">
          <thead class="text-xs font-semibold text-white uppercase bg-gray-700">
          <tr>
            <th v-if="showSelection" width="30" scope="col" class="p-2 whitespace-nowrap">
              <label class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                <input v-model="checkAll" :value="true" type="checkbox"
                       class="w-4 h-4 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                       @change="toggleCheckAll"/>
              </label>
            </th>

            <th v-for="column in columns" :key="column.key" scope="col" :width="column.width ? column.width : 'auto'"
                class="p-2 whitespace-nowrap">
              <div class="font-semibold text-left">
                {{ __(column.title) }}
              </div>
            </th>

            <th v-if="itemActions.length" :width="itemActions.length * 80" scope="col"
                class="p-2 text-center whitespace-nowrap">
              {{ __("Action") }}
            </th>
          </tr>
          </thead>
          <tbody class="text-sm divide-y divide-gray-100">
          <tr v-for="(item, index) in data" :class="{ 'border-b': index % 2 === 0 }">
            <td v-if="showSelection" class="p-2 whitespace-nowrap">
              <label :for="'checkbox-table-search-' + item[tableConfig.item_key]"
                     class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                <input :id="item[tableConfig.item_key]" v-model="selectedItems" :value="item" type="checkbox"
                       class="w-4 h-4 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
              </label>
            </td>

            <td v-for="column in columns" :key="column.key" :data-label="column.title"
                :class="'p-2 ' + (column.class ? column.class : '')">
              <!-- <template v-if="item.render">
                {{ item.render() }}
              </template> -->
              <slot :name="'cell[' + column.key + ']'" v-bind="{ item, column, index }">
                {{
                  $style["format"][column.key]
                    ? $style["format"][column.key](item[column.key])
                    : item[column.key]
                }}
              </slot>
            </td>
            <td v-if="itemActions.length" class="flex flex-wrap justify-center p-2 whitespace-nowrap">
              <!-- Modal toggle -->
              <template v-for="itemAction in itemActions">
                <slot :name="'cellAction[' + itemAction.key + ']'" v-bind="{
                    item,
                    itemAction,
                    actionMethod() {
                      itemAction.action(item, reload);
                    },
                  }">
                  <a-popconfirm v-if="itemAction.confirm" title="Are you sure？"
                                @confirm="itemAction.action(item, reload)">
                    <a-button :class="itemAction.class
                          ? itemAction.class
                          : 'font-medium text-blue-600 dark:text-blue-500 hover:underline'
                        " type="link">
                      {{ itemAction.label }}
                    </a-button>
                  </a-popconfirm>
                  <a-button v-else :class="itemAction.class
                        ? itemAction.class
                        : 'font-medium text-blue-600 dark:text-blue-500 hover:underline'
                      " type="link" @click="itemAction.action(item, reload)">
                    {{ itemAction.label }}
                  </a-button>
                </slot>
              </template>
            </td>
          </tr>
          </tbody>
        </table>
      </slot>
      <a-empty v-if="data?.data?.length === 0 && pagination.total === 0" class="my-10" :description="false"/>
    </div>

    <a-pagination v-if="showPagination && data && pagination?.total > pagination.perPage"
                  v-model:current="pagination.page" v-model:pageSize="pagination.perPage" style="height: 40px"
                  class="pt-2"
                  :show-size-changer="showSizeChanger" :total="pagination.total" @change="reload">
      <template #itemRender="{ type, originalElement }">
        <a v-if="type === 'prev'">Previous</a>
        <a v-else-if="type === 'next'">Next</a>
        <component :is="originalElement" v-else></component>
      </template>
    </a-pagination>
  </div>
</template>
<style scoped>
.item-actions > :not(:first-child) {
  margin-left: 10px;
}
</style>
