<script setup>
import {reactive, ref} from "vue";
import Api from "@/utils/Api";

const props = defineProps({
  value: Object
})

const validateMessages = {
  required: '${label} is required!',
  types: {
    email: '${label} is not a valid email!',
    number: '${label} is not a valid number!',
  },
  number: {
    range: '${label} must be between ${min} and ${max}',
  },
};

const emit = defineEmits(["success", "cancel"]);
const loading = ref(false)
const formState = reactive(props.value || {
  isNew: true,
  name: "",
  username: "",
  email: "",
});

const formConfig = reactive({
  "validateTrigger": "submit",
  "label-align": "top",
  "model": formState,
  labelCol: {span: 24},
  wrapperCol: {span: 24},
  "validate-messages": validateMessages,
});

const submit =async function () {
  loading.value=true
  const result = await Api.post('user',formState.value)
  emit('success' ,result.data)
}
const cancel = function () {
  emit('cancel')
}


</script>

<template>

  <a-form
    v-bind="formConfig"
    @finish="submit"
  >
    <a-form-item name="name" label="Full Name" :rules="[{ required: true }]">
      <a-input v-model:value="formState.name"/>
    </a-form-item>
    <a-form-item name="phone" label="Phone" :rules="[{ type: 'phone',required: true  }]">
      <a-input v-model:value="formState.phone"/>
    </a-form-item>

    <a-form-item>
      <a-space>
        <a-button :loading="loading" type="primary" html-type="submit">Submit</a-button>
        <a-button @click="cancel" html-type="button">Cancel</a-button>
      </a-space>
    </a-form-item>
  </a-form>

</template>
