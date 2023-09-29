<script>
import { defineComponent, ref, watch } from "vue";
import { BaseIcon } from ".";
import dayjs from "dayjs";
import router from "@/router";
import {
  deleteTask,
  completeTask,
  workingTask,
  reviewTask,
  getStatusTask
} from "@/utils/Task";
import { CheckCircleOutlined, ClockCircleOutlined, SyncOutlined } from "@ant-design/icons-vue";

export default defineComponent({
  name: "TaskItem",
  props: {
    class: {
      type: String,
      default: "bg-white shadow rounded-lg p-4",
    },
    value: {
      type: Object,
    },
  },
  methods: {
    viewPatient() {
      router.push("/patient/" + this.value.patient.id + "/edit");
    },
    edit() {
      router.push("/task/" + this.value.id + "/edit");
    },
    checkOutDate(task) {
      if (!task) return false;
      if (task.deadline_at) {
        return dayjs(task.deadline_at).isBefore(dayjs());
      }
    },
    getStatusTask,
  },
  emits: ["change", "update:value"],
  setup(props, { emit, attrs }) {
    props.value.created_at = dayjs(props.value.created_at).format("HH:mm MM-DD-YYYY");
    return {
      value: props.value,
      class: props.class,
    };
  },
  components: {
    CheckCircleOutlined,
    ClockCircleOutlined,
    SyncOutlined,
  },

});
</script>

<template>
  <div>
    <div :class="class">
      <div class="flex items-center justify-between">
        <small>{{ value.created_at }}</small>
        <a-tag v-if="getStatusTask(value.task_process)" :color="getStatusTask(value.task_process).color">
          <template #icon>
            <CheckCircleOutlined v-if="value.task_process === 3" />
            <SyncOutlined v-if="value.task_process === 1" :spin="true" />
            <ClockCircleOutlined v-if="[null, 0].includes(value.task_process)" />
          </template>
          {{ getStatusTask(value.task_process).label }}
        </a-tag>
      </div>
      <h5 class="my-2">
        <span class="text-base font-medium text-gray-700 dark:text-slate-400">{{
          value.name
        }}</span>
      </h5>
      <p class="space-x-3">
        <span v-if="value.patient" class="mb-2 text-nowrap">
          <a @click="viewPatient" class="cursor-pointer">
            {{ value.patient.full_name }}
          </a>
        </span>
      </p>
      <div v-if="value.assignees?.length" class="mt-5">
        <a-avatar-group :max-count="3" :max-style="{ color: '#f56a00', backgroundColor: '#fde3cf' }">
          <a-tooltip v-for="user in value.assignees" :key="user" :title="user.full_name" placement="top">
            <img class="relative w-8 h-8 border border-white rounded-full -me-1 hover:z-10" :src="user?.profile_photo_url"
              alt="user photo">
          </a-tooltip>
        </a-avatar-group>
      </div>
    </div>
  </div>
</template>
