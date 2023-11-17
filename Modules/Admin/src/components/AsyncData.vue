<template>
  awdawdadw
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
  emits: ['onload'],

  setup(props, {emit}) {
    const data = ref([]);
    alert(4343)
    console.log(323,props.async);
    if (props.async) {
      console.log(323,props.async);
      props.async.then(rs => {
        // data.value = rs
        emit('onload',data.value)
      })
    }
    return {
      data
    };
  },
});
</script>
