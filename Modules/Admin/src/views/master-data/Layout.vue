<script setup>
import {ref, h} from "vue";
import {AppstoreOutlined, MailOutlined} from '@ant-design/icons-vue';
import router from "@/router";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";

const items = ref([

    {
      key: 'task-status',
      icon: () => h(AppstoreOutlined),
      href: '/master-data/task-status',
      label: 'Task Status',
      title: 'Task Status',
    } ,

  ])
;

function menuClick({item}) {
  if (item.href) {
    router.replace(item.href)
  }
}
</script>

<template>
  <LayoutAuthenticated>
    <a-layout>
      <a-menu @click="menuClick" mode="horizontal" :items="items">
      </a-menu>
      <a-layout-content
        :style="{ background: '#fff', padding: '24px', margin: 0, minHeight: '280px' }"
      >
        <router-view/>
      </a-layout-content>
    </a-layout>
  </LayoutAuthenticated>

</template>
