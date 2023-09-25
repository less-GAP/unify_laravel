<script setup>
import {defineComponent, ref, unref} from 'vue';
import {PlusOutlined, IdcardOutlined} from '@ant-design/icons-vue';
import {mdiDoctor} from "@mdi/js";
import {BaseIcon} from "@/components";
</script>
<template>
  <a-dropdown :trigger="['click']">

    <button class="dropdown-toggle btn px-2 border inline-block shadow-sm rounded box" aria-expanded="false" data-tw-toggle="dropdown"><span class="w-5 h-5 flex items-center justify-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide w-4 h-4"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></span></button>
    <template #overlay>
      <a-menu>
        <a-menu-item>
          <router-link to="patient/new">
            <IdcardOutlined class="mr-2"/>
            New Patient
          </router-link>
        </a-menu-item>
        <a-menu-item>
          <router-link to="doctor/new">
            <BaseIcon class="inline-block mr-2" :path="mdiDoctor"></BaseIcon>
            New Doctor
          </router-link>
        </a-menu-item>

      </a-menu>
    </template>
  </a-dropdown>
</template>
