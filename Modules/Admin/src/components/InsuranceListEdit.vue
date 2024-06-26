<template>
  <div
    class="ant-table ant-table-ping-right ant-table-layout-fixed ant-table-fixed-header ant-table-fixed-column ant-table-scroll-horizontal ant-table-has-fix-right ant-table-small ant-table-bordered">
    <div class="ant-table-container">
      <table>
        <thead class="ant-table-thead">
          <tr>
            <th class="ant-table-cell ant-table-cell-ellipsis" v-class="column.class"
              v-for="(column, columnIndex) in getColumns()" scope="col">
              <slot name="column" v-bind="{ column }">
                <template v-if="column.dataIndex == 'action'">
                  <a-button @click="newValue.push({})">
                    <template #icon>
                      <PlusOutlined></PlusOutlined>
                    </template>
                    {{ __('Add Coverage') }}
                  </a-button>
                </template>
                <template v-else>
                  {{ column.title }}
                  <a-button size="small" v-if="editColumn && column.dataIndex !== 'action'"
                    @click="columns.splice((columnIndex - 1), 1)" style="float:right" type="link" danger>
                    <template #icon>
                      <Icon icon="ion:remove-outline"></Icon>
                    </template>
                  </a-button>
                </template>
              </slot>
            </th>
          </tr>
        </thead>
        <draggable v-bind="dragOptions" v-model="newValue" class="ant-table-tbody" handle=".drag-handle" tag="tbody">
          <template #item="{ element, index }">
            <tr class="ant-table-measure-row">
              <td v-for="column in getColumns()" scope="row">
                <template v-if="column.dataIndex == 'action'">
                  <div class="flex items-center justify-end">
                    <a-button type="link" primary>
                      <template #icon>
                        <DragOutlined class="drag-handle"></DragOutlined>
                      </template>
                    </a-button>
                    <a-button @click="newValue.splice(index, 1)" style="margin-left:10px" type="link" danger>
                      <template #icon>
                        <DeleteOutlined></DeleteOutlined>
                      </template>
                    </a-button>
                  </div>
                </template>
                <template v-else>
                  <slot :name="'bodyCell[' + column.dataIndex + ']'" v-bind="{ record: element, column }">
                    <a-input-number :min="column.min"
                      :formatter="value => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                      :parser="value => value.replace(/\$\s?|(,*)/g, '')" v-if="column.type == 'number'"
                      v-model:value="element[column.dataIndex]"></a-input-number>

                    <a-select v-else-if="column.dataIndex == 'coverage'" v-model:value="element[column.dataIndex]"
                      class="w-full" :options="listCoverage">
                    </a-select>

                    <a-date-picker v-else-if="column.type == 'date'" v-model:value="element[column.dataIndex]"
                      valueFormat="YYYY-MM-DD" format="MM-DD-YYYY" inputReadOnly class="w-full"></a-date-picker>
                    <a-input v-else v-model:value="element[column.dataIndex]"></a-input>
                  </slot>
                </template>
              </td>
            </tr>
          </template>
        </draggable>

      </table>
    </div>

  </div>
</template>

<script lang="ts">
import { computed, defineComponent, watch, ref, onMounted, unref, toRaw } from 'vue';
import { isArray, isFunction } from '@/utils/is';
import { DragOutlined, DeleteOutlined, PlusOutlined } from '@ant-design/icons-vue';
import draggable from "vuedraggable";
import type { FormInstance } from "ant-design-vue";
import Api from '@/utils/Api';

export default defineComponent({
  components: { draggable, DragOutlined, DeleteOutlined, PlusOutlined },
  props: {
    value: Array,
    columns: Array,
    editColumn: Boolean,
  },
  emits: ['options-change', 'change'],
  setup(props, { emit }) {
    const isFirstLoaded = ref<Boolean>(false);
    const loading = ref(false);
    const showAddColumn = ref(false);
    const columnForm = ref({});
    const newValue = ref(toRaw(props.value));
    const formRef = ref<FormInstance>();
    const listCoverage = ref([]);

    onMounted(async () => {
      const { data } = await Api.get('master-data/insurance');
      listCoverage.value = JSON.parse(data.data).map((item) => {
        return {
          label: item.label,
          value: item.value,
        };
      });
    });

    if (!Array.isArray(props.value)) {
      props.value = []
      emit('update:value', [])
    }

    function handleChange(...args) {
      emit('change', ...args);
    }

    watch(
      () => newValue.value,
      (value) => {
        emit('update:value', value)
      },
      { deep: true },
    );
    watch(
      () => props.value,
      (value) => {
        if (!Array.isArray(value)) {
          newValue.value = []
        } else {
          newValue.value = value
        }
      },
      { deep: true },
    );


    return {

      getColumns() {
        if (!props.columns) {
          return []
        }
        return [{
          title: '',
          dataIndex: 'action',
          width: 100,
          class: 'w-[70px]'
        }].concat(props.columns)
      },
      addColumn() {
        formRef.value
          .validateFields()
          .then(value => {
            showAddColumn.value = false;
            if (!props.columns) {
              props.columns = []
            }
            props.columns.push({ title: value.dataIndex, dataIndex: value.dataIndex });
            formRef.value.resetFields();
          })

      },
      changeColumn(a, b) {
        console.log(a, b)
      },
      dragOptions: {
        animation: 200,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      },
      loading,
      listCoverage,
      showAddColumn,
      columnForm,
      formRef,
      newValue,
      handleChange
    };
  },
});
</script>

<style>
.ant-table-measure-row td:first-child {
  width: 180px;
}

.flip-list-move {
  transition: transform 0.5s;
}

.no-move {
  transition: transform 0s;
}

.ghost {
  opacity: 0.5;
  background: #ddd;
}

.list-group {
  min-height: 20px;
}

.list-group-item {
  cursor: move;
}

.list-group-item i {
  cursor: pointer;
}
</style>
