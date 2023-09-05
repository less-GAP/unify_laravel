<template>
  <div class="p-5">
    <slot name="headAction">
      <template>
        <a-button @click="newValue.push({})">
          <template #icon>
            <PlusOutlined></PlusOutlined>
          </template>
          {{ __('Add Insurance') }}
        </a-button>
      </template>
    </slot>
  </div>
  <draggable v-bind="dragOptions" v-model="newValue" class="ant-table-tbody" handle=".drag-handle" tag="tbody">
    <template #item="{ element, index }">
      <a-card v-for="column in getColumns()">
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
        <template>
          <slot :name="'bodyCell[' + column.dataIndex + ']'" v-bind="{ record: element, column }">
            <a-input-number :min="column.min" :formatter="value => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
              :parser="value => value.replace(/\$\s?|(,*)/g, '')" v-if="column.type == 'number'"
              v-model:value="element[column.dataIndex]"></a-input-number>
            <a-input v-else v-model:value="element[column.dataIndex]"></a-input>
          </slot>
        </template>
      </a-card>
    </template>
  </draggable>
</template>

<script lang="ts">
import { computed, defineComponent, watch, ref, onMounted, unref, toRaw } from 'vue';
import { isArray, isFunction } from '@/utils/is';
import { DragOutlined, DeleteOutlined, PlusOutlined } from '@ant-design/icons-vue';
import draggable from "vuedraggable";
import type { FormInstance } from "ant-design-vue";

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

    if (!props.value) {
      props.value = []
      emit('update', [])
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
        newValue.value = value
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
