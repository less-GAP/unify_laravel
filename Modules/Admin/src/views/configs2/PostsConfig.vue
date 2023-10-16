<script setup>
import { reactive, ref } from "vue";
import Api from "@/utils/Api";
import validateMessages from "@/utils/validateMessages";
import draggable from "vuedraggable";

const lists = reactive({
  list1: [
    { key: "info", name: "Info" },
    { key: "title", name: "Title" },
    { key: "slug", name: "Slug" },
    { key: "status", name: "Status" },
  ],
  list2: [
    { key: "id", name: "ID", disable: true },
    { key: "name", name: "Name" },
    { key: "code", name: "Code" },
    { key: "email", name: "Email" },
    { key: "phone", name: "Phone" },
    { key: "address", name: "Address" },
    { key: "action", name: "Action", disable: true },
  ],
});

const listUser = ref();

const configNames = ["per_page", "table_columns", "default_seller"];

const emit = defineEmits(["success", "cancel"]);
const loading = ref(false);
const error = ref(null);
const formState = reactive({});
const formConfig = reactive({
  validateTrigger: "submit",
  "label-align": "top",
  model: formState,
  labelCol: { span: 24 },
  wrapperCol: { span: 24 },
  "validate-messages": validateMessages,
});
const fetch = function () {
  loading.value = true;
  Api.get("user/all").then((result) => {
    listUser.value = result.data.map((item) => {
      return {
        label: item.full_name,
        value: item.id,
      };
    });
  });
  Api.get("config", { params: { names: configNames } })
    .then((result) => {
      Object.assign(formState, result.data);
      formState.default_seller = formState.default_seller
        ? parseInt(formState.default_seller)
        : null;
    })
    .catch((e) => {})
    .finally((loading.value = false));
};
fetch();
const submit = async function () {
  loading.value = true;
  Api.post("config", formState)
    .then((result) => {
      emit("success", result);
    })
    .catch((e) => {})
    .finally((loading.value = false));
};

const cancel = function () {
  emit("cancel");
};

const updateList = function (evt) {
  // Update list1
  lists.list1.splice(evt.newIndex, 0, lists.list1.splice(evt.oldIndex, 1)[0]);
  // Update list2
  lists.list2.splice(evt.oldIndex, 0, lists.list2.splice(evt.newIndex, 1)[0]);
  // console.log(lists.list1)
  // console.log(lists.list2)
};
</script>

<template>
  <a-form autocomplete="off" v-bind="formConfig" @finish="submit">
    <a-form-item
      name="default_seller"
      label="Default Seller"
      :rules="[{ required: true }]"
      class="w-[200px]"
    >
      <a-select
        v-model:value="formState.default_seller"
        :options="listUser"
      ></a-select>
    </a-form-item>
    <a-form-item name="per_page" label="Per Page" :rules="[{ required: true }]">
      <a-input-number
        v-model:value="formState.per_page"
        size="large"
        :min="1"
        :max="1000"
      />
    </a-form-item>

    <div class="flex mb-5 space-x-5">
      <div class="w-auto">
        <h3
          class="p-2 font-bold bg-gray-100 border-2 border-gray-300 border-b-1"
        >
          Table columns
        </h3>
        <draggable
          class="border border-b-0 border-gray-300 list-group"
          :list="lists.list1"
          group="people"
          item-key="key"
          @change="updateList"
        >
          <template #item="{ element }">
            <div
              :key="element.key"
              class="p-2 bg-white border-b border-gray-300 list-group-item"
              :disabled="element.disable"
              :class="{ 'pointer-events-none	opacity-50': element.disable }"
            >
              {{ element.name }}
            </div>
          </template>
        </draggable>
      </div>

      <div class="w-auto">
        <h3
          class="p-2 font-bold bg-gray-100 border-2 border-gray-300 border-b-1"
        >
          Your columns
        </h3>
        <draggable
          name="table_columns"
          class="border border-b-0 border-gray-300 list-group"
          :list="lists.list2"
          group="people"
          item-key="key"
          @change="updateList"
        >
          <template #item="{ element }">
            <div
              :key="element.key"
              class="p-2 bg-white border-b border-gray-300 list-group-item"
              :disabled="element.disable"
              :class="{ 'pointer-events-none	opacity-50': element.disable }"
            >
              {{ element.name }}
            </div>
          </template>
        </draggable>
      </div>
    </div>

    <a-form-item>
      <a-space>
        <a-button :loading="loading" type="primary" html-type="submit"
          >Submit</a-button
        >
      </a-space>
    </a-form-item>
  </a-form>
</template>
