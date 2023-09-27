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
    value: {
      type:[Object,Array],
      default: []
    },
  },
  emits: ['load'],

  setup(props, {emit}) {
    const data = ref([]);
    if (props.async) {
      props.async.then(rs => {
        data.value = rs
        emit('load',data.value)
      })
    }
    return {
      data
    };
  },
});
</script>
