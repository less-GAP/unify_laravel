<script>
import {defineComponent, watch, ref, unref} from 'vue';
import {Tooltip} from 'ant-design-vue';
import {SearchOutlined} from '@ant-design/icons-vue';
import AppSearchModal from './AppSearchModal.vue';
import {useMagicKeys} from '@vueuse/core';


export default defineComponent({
  name: 'AppSearch',
  components: {SearchOutlined, AppSearchModal},
  setup() {
    const modal = ref()
    const changeModal = function (){
       modal.value.changeModal(true)
    }
    return {
      modal,
      changeModal
    }
  }
})
</script>
<template>
  <div class="p-1">
    <div className="search hidden sm:block">
      <a-input :readonly="true" @click="changeModal(true)" placeholder="Quick Search... (Ctrl+k)">
        <template #suffix>
          <SearchOutlined/>

        </template>
      </a-input>
    </div>
    <AppSearchModal ref="modal"></AppSearchModal>
  </div>
</template>
