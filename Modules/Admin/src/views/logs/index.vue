<script setup>
import router from "@/router";
import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import dayjs from "dayjs";
import { ref, watch } from "vue";
import { useAuthStore } from "@/stores/auth";
import { DataTable } from "@/components";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import { UseDataTable } from "@/utils/UseDataTable";
import { mdiGenderMale, mdiGenderFemale, mdiPencil } from "@mdi/js";
import { BaseIcon } from "@/components";
import Api from "@/utils/Api";

const prefix = "logs";
const { fetchListApi } = UseEloquentRouter(prefix);
const auth = useAuthStore();
var item = {};
const itemActions = [
    //   {
    //     label: "Edit",
    //     key: "edit",
    //     show: () => {
    //       return true;
    //     },
    //     action: (item) => {
    //       router.replace(prefix + "/" + item.id + "/edit");
    //     },
    //   },
];
const listActions = [
    //   {
    //     label: "Add",
    //     action: () => {
    //       router.replace(prefix + "/new");
    //     },
    //   },
];
const columns = [
    {
        title: "Date time",
        key: "created_at",
        width: 180,
    },
    {
        title: "Module",
        key: "subject_type",
        width: 100,
    },
    {
        title: "ID",
        key: "subject_id",
        width: 100,
    },
    //   {
    //     title: "Name",
    //     key: "users",
    //     width: 100,
    //   },
    {
        title: "Work Description",
        key: "description",
        width: "",
    },
    {
        title: "By user",
        key: "causer",
        width: 120,
    },
    {
        title: "Event",
        key: "event",
        width: 100,
    },
];

const nameAssignee = (user, isFull) => {
  if (user && user.full_name) {
    if (isFull) {
      return user.full_name;
    } else {
      const fullNameParts = user.full_name.split(" ");
      // if (fullNameParts.length >= 2) {
      const lastWord = fullNameParts[fullNameParts.length - 1];
      if (lastWord.length > 0) {
        return lastWord.charAt(0);
      }
      // }
    }
  }
  return "N/A";
};

const getModuleLog = (module) => {
    return module ? module.split('\\').pop() : '';
}

const tableConfig = UseDataTable(fetchListApi, {
    columns,
    showSelection: false,
    globalSearch: false,
    showSort: false,
    listActions,
    itemActions,
});
let reloadTable = () => { };

watch(router.currentRoute, (currentRoute) => {
    if (currentRoute.path === "/" + prefix) {
        reloadTable();
    }
});

function registerTable({ reload }) {
    reloadTable = reload;
}
</script>

<template>
    <LayoutAuthenticated>
        <SectionMain>
            <DataTable v-bind="tableConfig" @register="registerTable">
                <template #header>
                    <h2>Logs</h2>
                </template>
                <template #cell[subject_id]="{ item }">
                    {{ item.subject_id }}
                </template>
                <template #cell[causer]="{ item }">
                      <a-avatar-group>
                        <a-tooltip
                          :title="nameAssignee(item.causer, true)"
                          placement="top"
                        >
                          <a-avatar
                            style="background-color: #87d068"
                            >{{ nameAssignee(item.causer) }}</a-avatar
                          >
                        </a-tooltip>
                      </a-avatar-group>
                </template>
                <template #cell[subject_type]="{ item }">
                    {{ getModuleLog(item.subject_type) }}
                </template>
            </DataTable>
            <router-view></router-view>
        </SectionMain>
    </LayoutAuthenticated>
</template>
