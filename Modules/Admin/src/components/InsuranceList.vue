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
  <a-card v-for="item in getItems()" v-model="newValue" class="relative">
    <a-button @click="newValue.splice(index, 1)" type="link" danger class="absolute">
      <template #icon>
        <DeleteOutlined></DeleteOutlined>
      </template>
    </a-button>
    <div>
      <a-form-item >
        <a-select
        :options="newValue"
        >
        </a-select>
      </a-form-item>
    </div>
  </a-card>
</template>

<script lang="ts">
import { computed, defineComponent, watch, ref, onMounted, unref, toRaw } from 'vue';
import { isArray, isFunction } from '@/utils/is';
import { DragOutlined, DeleteOutlined, PlusOutlined } from '@ant-design/icons-vue';
import draggable from "vuedraggable";
import type { FormInstance } from "ant-design-vue";
import { fetchListInsurancesApi } from '@/utils/Patient'

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
    const showAddItem = ref(false);
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

      getItems() {
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
      addItem() {
        formRef.value
          .validateFields()
          .then(value => {
            showAddItem.value = false;
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
      showAddItem,
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
