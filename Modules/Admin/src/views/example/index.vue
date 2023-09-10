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
      <RemoteSelect class="w-[200px]" v-model:value="formState.patient_status" url="master-data/task-status">
      </RemoteSelect>
      <ApiData url="master-data/task-status" method="GET" :params="{}">
        <template #default="{data}">
          {{ data.data }}
        </template>
      </ApiData>
      <a-divider>Remote Select Component</a-divider>
      <RemoteSelect class="w-[200px]" v-model:value="formState.patient_status" url="master-data/task-status">
      </RemoteSelect>
      <a-divider>Combine and reload</a-divider>
      <RemoteSelect class="w-[200px]" labelKey="full_name" valueKey="id" v-model:value="formState.user" url="user/all">
      </RemoteSelect>
      <ApiData v-if="formState.user" :url="'user/'+formState.user" method="GET" >
        <template #default="{data}">
          {{ data }}
        </template>
      </ApiData>
      <div v-if="$auth.hasPermission('patient.list')">
        Check permission patient.list
      </div>
      <div v-if="$auth.hasPermission('patient.edit')">
        Check permission patient.edit
      </div>
    </SectionMain>
  </LayoutAuthenticated>
</template>
