<script setup>
import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue"
import {ApiData, RemoteSelect} from "@/components";
import {reactive} from "vue";

const formState = reactive({})
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <a-divider>ApiData Component</a-divider>
      <ApiData url="master-data/patient-status/options" method="GET" :params="{}">
        <template #default="{data}">
          {{ data }}
        </template>
      </ApiData>
      <a-divider>Remote Select Component</a-divider>
      <RemoteSelect class="w-[200px]" v-model:value="formState.patient_status" url="master-data/patient-status/options">
      </RemoteSelect>
      <RemoteSelect class="w-[200px]" labelKey="full_name" valueKey="id" v-model:value="formState.test" url="user/all">
      </RemoteSelect>
    </SectionMain>
  </LayoutAuthenticated>
</template>
