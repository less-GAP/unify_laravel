<script setup>
import { containerMaxW } from "@/config.js";
</script>

<template>
  <section class="p-6 bg-white" :class="containerMaxW">
    <slot />
  </section>
</template>
