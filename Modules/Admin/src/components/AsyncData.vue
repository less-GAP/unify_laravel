<template>
  <slot v-if="data" v-bind="{data}"></slot>
</template>
<script lang="ts">
import {defineComponent, ref, watch, unref, computed} from 'vue';

export default defineComponent({
  components: {},
  props: {
    async: {
      type: Promise
    },
    default: {
      default: []
    },
  },
  emits: ['change', 'update:value'],

  setup(props, {emit}) {
    const data = ref([]);
    if (props.async) {
      props.async.then(rs => {
        data.value = rs
      })
    }
    return {
      data
    };
  },
});
</script>
