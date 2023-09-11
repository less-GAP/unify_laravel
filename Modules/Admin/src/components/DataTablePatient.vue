<script setup>
import { computed, ref, toRaw, h } from "vue";
import { Input } from "@/components/index";
import { ReloadOutlined } from "@ant-design/icons-vue";
import { fetchListStatusPatientApi } from "@/utils/Patient";
import Api from "@/utils/Api";

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
  showReload: {
    type: Boolean,
    default: true,
  },
  showSelection: {
    type: Boolean,
    default: true,
  },
  showSort: {
    type: [Boolean, Array],
    default: false,
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
const statusTaskRaw = ref({});
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
const fetchListUserApi = function () {
  return Api.get('user/all')
};

const fetchTaskByPatientApi = function (patient_id) {
  return Api.get('task/list?filter[patient_id]=' + patient_id + '&filter[deleted]=0');
};

async function reload() {
  if (props.api) {
    loading.value = true;
    try {
      var listUserAssignees = await fetchListUserApi().then((res) => res.data);
      var listStatusPatient = await fetchListStatusPatientApi().then((res) => res.data);
      listStatusPatient = JSON.parse(listStatusPatient.data)
      
      const rs = await props.api({
        perPage: props.pagination.perPage,
        sort: orderBy.value,
        page: props.pagination.page,
        ...props.params,
        ...getFilter(),
      });

      tableData.value = rs.data;
      props.pagination.total = rs.data?.total ? rs.data.total : 0;

      const statusPromises = tableData.value.data.map(async (item) => {
        if (item.unify_task_status !== null) {
          const status = listStatusPatient.find((status) => {
            return status.value == item.unify_task_status;
          })
          item.unify_task_status = status;
        }
        // if (item.assigned !== null) {
        //   item.assigned = oneCharName(item.assigned);
        // }
      });

      await Promise.all(statusPromises);
    } catch (error) {
      console.error("Error reloading data:", error);
    } finally {
      checkAll.value = false;
      loading.value = false;
    }
  }
}

emit("register", { reload, filter });
const loading = ref(false);
const checkAll = ref(false);
const selectedItems = ref([]);

function toggleCheckAll() {
  if (checkAll.value) {
    selectedItems.value = toRaw(tableData?.value.data || []);
  } else {
    selectedItems.value = [];
  }
}

reload();
</script>

<template>
  <div id="table-list" class="flex flex-col sm:rounded-lg">
    <slot name="header" v-bind="{ tableConfig, filter, reload }"> </slot>

    <div
      :loading="loading"
      class="flex items-center justify-between p-2 bg-white border border-gray-200 rounded-xl"
    >
      <a-space>
        <a-input-search
          v-if="tableConfig.globalSearch"
          v-model:value="filter.search"
          allow-clear
          style="max-width: 300px"
          placeholder="Enter to search..."
          :loading="loading"
          @search="reload"
          @keyup.enter="reload"
        />
        <slot name="filter" v-bind="{ tableConfig, filter, reload }"></slot>
        <slot name="sort" v-bind="{ tableConfig, sort, filter, reload }">
          <a-select
            v-if="showSort"
            v-model:value="orderBy"
            style="width: 140px"
            placeholder="Order by..."
            @change="reload"
          >
            <a-select-option
              v-for="sort in showSort"
              :key="sort.value"
              :value="sort.value"
              >{{ sort.label }}
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
        <a-button
          v-for="listAction in listActions"
          type="primary"
          @click="
            () => {
              listAction.action(reload);
            }
          "
          >{{ listAction.label }}</a-button
        >
      </a-space>
    </div>
    <div
      class="flex-1 w-full my-5 overflow-auto bg-white border border-gray-200 rounded-lg shadow scroll-smooth"
    >
      <a-skeleton v-if="loading || !tableData.data" active class="p-10" />

      <slot
        v-else
        name="table"
        v-bind="{ tableConfig, tableData, columns, selectionActions, reload }"
      >
        <table class="w-full text-xs table-auto">
          <thead class="font-semibold text-gray-400 uppercase bg-gray-50">
            <tr>
              <th
                v-if="showSelection"
                scope="col"
                width="40"
                class="px-1 py-2 text-center whitespace-nowrap"
              >
                <label class="w-full font-medium text-gray-900">
                  <input
                    v-model="checkAll"
                    :value="true"
                    type="checkbox"
                    class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                    @change="toggleCheckAll"
                  />
                </label>
              </th>

              <th
                v-for="column in columns"
                scope="col"
                :width="column.width ? column.width : 'auto'"
                class="px-1 py-2 whitespace-nowrap"
              >
                <div class="font-semibold text-left">
                  {{ __(column.title) }}
                </div>
              </th>

              <th
                v-if="itemActions.length"
                width="120"
                scope="col"
                class="px-1 py-2"
              >
                {{ __("Action") }}
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr
              v-for="(item, index) in tableData.data"
              :key="item[tableConfig.item_key]"
              :class="{ 'border-b': index % 2 === 0 }"
            >
              <td
                v-if="showSelection"
                width="40"
                class="p-1 text-center whitespace-nowrap"
              >
                <label
                  :for="'checkbox-table-search-' + item[tableConfig.item_key]"
                  class="w-full font-medium text-gray-900"
                >
                  <input
                    :id="item[tableConfig.item_key]"
                    v-model="selectedItems"
                    :value="item"
                    type="checkbox"
                    class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                  />
                </label>
              </td>

              <td
                v-for="column in columns"
                :data-label="column.title"
                :class="'p-1 ' + (column.class ? column.class : '')"
              >
                <template v-if="item.render">
                  {{ item.render() }}
                </template>
                <slot
                  v-else-if="column.key == 'unify_task_status'"
                  v-bind="{ item, column, index }"
                >
                  <a-tag v-if="item.unify_task_status !== null" :style="'background-color: '+item.unify_task_status.background_color+'; color: '+item.unify_task_status.color+'; border-color: currentColor' ">{{
                    item.unify_task_status.label
                  }}</a-tag>
                </slot>
                <slot
                  v-else-if="column.key == 'assigned'"
                  v-bind="{ item, column, index }"
                >
                
                </slot>
                <slot
                  v-else
                  :name="'cell[' + column.key + ']'"
                  v-bind="{ item, column, index }"
                >
                  {{
                    $style["format"][column.key]
                      ? $style["format"][column.key](item[column.key])
                      : item[column.key]
                  }}
                </slot>
              </td>
              <td
                v-if="itemActions.length"
                width="120"
                class="p-1 whitespace-nowrap"
              >
                <div class="flex flex-nowrap whitespace-nowrap">
                  <template v-for="itemAction in itemActions">
                    <slot
                      v-if="itemAction.show(item)"
                      :name="'cellAction[' + itemAction.key + ']'"
                      v-bind="{
                        item,
                        itemAction,
                        actionMethod() {
                          itemAction.action(item, reload);
                        },
                      }"
                    >
                      <a-button
                        :class="
                          itemAction.class ||
                          'font-medium text-blue-600 dark:text-blue-500 hover:underline'
                        "
                        type="link"
                        @click="itemAction.action(item, reload)"
                      >
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
      <a-empty
        v-if="tableData.data?.length === 0 && pagination.total === 0"
        class="my-10"
        :description="false"
      />
    </div>

    <a-pagination
      v-if="tableData.data && pagination?.total"
      v-model:current="pagination.page"
      v-model:pageSize="pagination.perPage"
      style="height: 40px"
      class="pt-2"
      :show-size-changer="showSizeChanger"
      :total="pagination.total"
      @change="reload"
    >
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
