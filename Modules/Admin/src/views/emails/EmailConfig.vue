<script setup>
import {reactive, ref} from "vue";
import Api from "@/utils/Api";
import validateMessages from "@/utils/validateMessages";
import draggable from "vuedraggable";
import {RemoteSelect} from "@/components";


const listUser = ref();
const configNames = ["email_template_register", "email_template_thankyou"];

const emit = defineEmits(["success", "cancel"]);
const loading = ref(false);
const error = ref(null);
const formState = reactive({});
const formConfig = reactive({
  validateTrigger: "submit",
  "label-align": "top",
  model: formState,
  labelCol: {span: 24},
  wrapperCol: {span: 24},
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
  Api.get("config", {params: {names: configNames}})
    .then((result) => {
      Object.assign(formState, result.data);
      formState.default_seller = formState.default_seller
        ? parseInt(formState.default_seller)
        : null;
    })
    .catch((e) => {
    })
    .finally((loading.value = false));
};
fetch();
const submit = async function () {
  loading.value = true;
  Api.post("config", formState)
    .then((result) => {
      emit("success", result);
    })
    .catch((e) => {
    })
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
      name="email_template_register"
      label="Register Email"
      :rules="[{ required: true }]"
    >
      <RemoteSelect
        url="email-template/all"
        valueKey="id"
        labelKey="email_title"
        v-model:value="formState.email_template_register"
      ></RemoteSelect>
    </a-form-item>
    <a-form-item
      name="email_template_thankyou"
      label="Thank You Email"
      :rules="[{ required: true }]"
    >
      <RemoteSelect
        url="email-template/all"
        valueKey="id"
        labelKey="email_title"
        v-model:value="formState.email_template_thankyou"
      ></RemoteSelect>
    </a-form-item>

    <a-form-item>
      <a-space>
        <a-button :loading="loading" type="primary" html-type="submit"
        >Submit
        </a-button
        >
      </a-space>
    </a-form-item>
  </a-form>
</template>
