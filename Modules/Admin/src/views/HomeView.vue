<script setup>
import { computed, ref, onMounted } from "vue";
import { useMainStore } from "@/stores/main";
import {
  mdiAccountMultiple,
  mdiCartOutline,
  mdiChartTimelineVariant,
} from "@mdi/js";
import * as chartConfig from "@/components/Charts/chart.config.js";
import SectionMain from "@/components/SectionMain.vue";
import CardBoxWidget from "@/components/CardBoxWidget.vue";
import Calendar from "@/components/Calendar.vue";
import CardBoxTransaction from "@/components/CardBoxTransaction.vue";
import CardBoxClient from "@/components/CardBoxClient.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";

const chartData = ref(null);

const fillChartData = () => {
  chartData.value = chartConfig.sampleChartData();
};

onMounted(() => {
  fillChartData();
});

const mainStore = useMainStore();

const clientBarItems = computed(() => mainStore.clients.slice(0, 4));

const transactionBarItems = computed(() => mainStore.history);
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>

      <SectionTitleLineWithButton
        :icon="mdiChartTimelineVariant"
        title="Unify Health Care"
        main
      >
      </SectionTitleLineWithButton>
      <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-5">
        <CardBoxWidget
          class="text-blue-500"
          color="!text-blue-500"
          :icon="mdiAccountMultiple"
          :number="$appState.statistics.patients_count"
          label="Total"
        />
        <CardBoxWidget
          class="text-yellow-500"
          color="!text-yellow-500"
          :icon="mdiAccountMultiple"
          :number="$appState.statistics.patients_waiting_count"
          label="Waiting"
        />
        <CardBoxWidget
          class="text-orange-500"
          color="!text-orange-500"
          :icon="mdiAccountMultiple"
          :number="$appState.statistics.patients_check_count"
          label="Eligibility Check"
        />
        <CardBoxWidget
          class="text-green-500"
          color="!text-green-500"
          :icon="mdiAccountMultiple"
          :number="$appState.statistics.patients_active_count"
          label="Active"
        />
        <CardBoxWidget
          class="text-gray-500"
          color="!text-gray-500"
          :icon="mdiAccountMultiple"
          :number="$appState.statistics.patients_inactive_count"
          label="InActive"
        />
      </div>

      <!-- <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
        <div class="flex flex-col justify-between">
          <CardBoxTransaction
            v-for="(transaction, index) in transactionBarItems"
            :key="index"
            :amount="transaction.amount"
            :date="transaction.date"
            :business="transaction.business"
            :type="transaction.type"
            :name="transaction.name"
            :account="transaction.account"
          />
        </div>
        <div class="flex flex-col justify-between">
          <CardBoxClient
            v-for="client in clientBarItems"
            :key="client.id"
            :name="client.name"
            :login="client.login"
            :date="client.created"
            :progress="client.progress"
          />
        </div>
      </div> -->

      <!-- <Calendar /> -->

    </SectionMain>
  </LayoutAuthenticated>
</template>
