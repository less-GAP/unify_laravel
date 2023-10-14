<script setup>
  import {reactive, ref} from "vue";
  import {message} from 'ant-design-vue';

  import Api from "@/utils/Api";
  import validateMessages from "@/utils/validateMessages";
  import {InputUpload, InputUploadGetPath} from "@/components";

  // export default {
  //   components: {}
  // }


  const configNames = [
    'paypal_client_id',
    'paypal_client_secret',
    'stripe_publishable_key',
    'stripe_secret_key',
    'square_application_id',
    'square_location_id',
    'square_access_token',
  ]

  const emit = defineEmits(["success", "cancel"]);
  const loading = ref(false)
  const error = ref(null)
  const formState = reactive({});
  const formConfig = reactive({
    "validateTrigger": "submit",
    "label-align": "top",
    "model": formState,
    labelCol: {span: 24},
    wrapperCol: {span: 24},
    "validate-messages": validateMessages,
  });
  const fetch = function () {
    loading.value = true
    Api.get('config', {params: {names: configNames}}).then(result => {
      Object.assign(formState, result.data)
    }).catch(e => {
    }).finally(loading.value = false)
  }
  fetch();
  const submit = async function () {
    loading.value = true
    Api.post('config', formState).then(result => {
      emit('success', result)
    }).catch(e => {
    }).finally(loading.value = false)
  }

  const cancel = function () {
    emit('cancel')
  }

</script>

<template>

  <a-form
    autocomplete="off"
    v-bind="formConfig"
    @finish="submit"
  >
    
    <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="0" plain>Paypal</a-Divider>
    <a-form-item name="paypal_client_id" label="Paypal Client ID">
        <a-input autocomplete="off" v-model:value="formState.paypal_client_id" class="rounded border-gray-300"/>
    </a-form-item>

    <a-form-item name="paypal_client_secret" label="Paypal Client Secret">
        <a-input autocomplete="off" v-model:value="formState.paypal_client_secret" class="rounded border-gray-300"/>
    </a-form-item>

    <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="0" plain>Stripe</a-Divider>
    <a-form-item name="stripe_publishable_key" label="Stripe Publishable Key">
        <a-input autocomplete="off" v-model:value="formState.stripe_publishable_key" class="rounded border-gray-300"/>
    </a-form-item>

    <a-form-item name="stripe_secret_key" label="Stripe Secret Key">
        <a-input autocomplete="off" v-model:value="formState.stripe_secret_key" class="rounded border-gray-300"/>
    </a-form-item>

    <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="0" plain>Square</a-Divider>

    <a-form-item name="square_application_id" label="Square Application ID">
        <a-input autocomplete="off" v-model:value="formState.square_application_id" class="rounded border-gray-300"/>
    </a-form-item>

    <a-form-item name="square_location_id" label="Square Location ID">
        <a-input autocomplete="off" v-model:value="formState.square_location_id" class="rounded border-gray-300"/>
    </a-form-item>

    <a-form-item name="square_access_token" label="Square Access Token">
        <a-input autocomplete="off" v-model:value="formState.square_access_token" class="rounded border-gray-300"/>
    </a-form-item>

    <a-form-item>
      <a-space>
        <a-button :loading="loading" type="primary" html-type="submit">Submit</a-button>
      </a-space>
    </a-form-item>
  </a-form>

</template>
