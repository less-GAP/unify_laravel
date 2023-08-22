<template>
  <div style="margin-bottom:15px">
    <a-button style="margin-right:15px" v-if="editColumn" @click="showAddColumn =true;columnForm={}" type="warning">{{
        __('Add Column')
      }}
    </a-button>
    <a-button @click="value.push({})" type="success">
      <template #icon>
        <Icon icon="ion:add-outline"></Icon>
      </template>
      {{ __('Add') }}
    </a-button>

  </div>
  <div
    class="ant-table ant-table-ping-right ant-table-layout-fixed ant-table-fixed-header ant-table-fixed-column ant-table-scroll-horizontal ant-table-has-fix-right ant-table-small ant-table-bordered">
    <div class="ant-table-container">
      <table>
        <thead class="ant-table-thead">
        <tr>
          <th class="ant-table-cell ant-table-cell-ellipsis" v-for="(column,columnIndex) in getColumns()" scope="col">
            <slot name="column" v-bind="{column}">
              {{ column.title }}
              <a-button size="small" v-if="editColumn && column.dataIndex !=='action'"
                        @click="columns.splice((columnIndex-1),1)" style="float:right" type="danger">
                <template #icon>
                  <Icon icon="ion:remove-outline"></Icon>
                </template>
              </a-button>
            </slot>
          </th>
        </tr>
        </thead>
        {{ newValue }}
        <div  v-if="newValue && newValue.length>0">

<!--        <draggable v-model="newValue" group="people" @start="drag=true" @end="drag=false" item-key="id">-->
<!--          <template #item="{element}">-->
<!--            <div>d23d232d3}</div>-->
<!--          </template>-->
<!--        </draggable>-->
        </div>

      </table>
    </div>
  </div>

  <a-modal
    title="Add Column"
    v-model:visible="showAddColumn"
    @ok="addColumn"
  >
    <a-form style="margin:20px" ref="formRef" :model="columnForm" layout="vertical" name="form_in_modal">
      <a-form-item
        name="key"
        label="Column Key"
        :rules="[{ required: true, message: 'Please input group name!' }]"
      >
        <a-input v-model:value="columnForm.key"/>
      </a-form-item>
    </a-form>

  </a-modal>
</template>

<script lang="ts">
import {computed, defineComponent, watch, ref, onMounted, unref, toRaw} from 'vue';
import {DragOutlined, DeleteOutlined} from '@ant-design/icons-vue';
import Draggable from 'vuedraggable';
import type {FormInstance} from "ant-design-vue";

export default defineComponent({
  name: 'ApiTree',
  components: {Draggable, DragOutlined, DeleteOutlined},
  props: {
    value: Array,
    columns: Array,
    editColumn: Boolean,
  },
  emits: ['options-change', 'change'],
  setup(props, {attrs, emit}) {
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
        emit('update', value)
      },
      {deep: true},
    );
    watch(
      () => props.value,
      (value) => {
        newValue.value = value
      },
      {deep: true},
    );


    return {

      getColumns() {
        if (!props.columns) {
          return []
        }
        return [{
          title: '',
          dataIndex: 'action',
          key: 'action'
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
            props.columns.push({title: value.key, key: value.key, dataIndex: value.key});
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
