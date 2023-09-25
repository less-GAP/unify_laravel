<template>
  <a-modal :closable="false" v-model:open="showModal">
    <div class="relative border-b border-slate-200/60">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
           class="lucide w-5 h-5 absolute inset-y-0 my-auto ml-4 text-slate-500">
        <circle cx="11" cy="11" r="8"></circle>
        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
      </svg>
      <input v-model="term" ref="searchInput" type="text"
             class="form-control border-0 shadow-none focus:ring-0 py-5 px-12"
             placeholder="Quick Search...">
      <div
        class="h-6 text-xs bg-slate-200 text-slate-500 px-2 flex items-center rounded-md absolute inset-y-0 right-0 my-auto mr-4">
        ESC
      </div>
    </div>
    <div :key="term" class="p-5">
      <template v-for="SearchItem in SearchItems">
        <AsyncData :async="SearchItem.search(term)">
          <template #default="{data}">
            <div v-if="data.length" class="font-medium mb-3">{{ SearchItem.label }}</div>
            <div v-if="data.length" class="mb-3">
              <a @click="doAction(item.action)" v-for="item in data"
                 class="flex bg-gray-200 hover:bg-gray-200 pl-2 pr-2 rounded items-center mt-3 first:mt-0">
                <div
                  v-if="item.icon"
                  class="w-7 h-7 bg-success/20 dark:bg-success/10 text-success flex items-center justify-center rounded-full">
                  <BaseIcon :path="item.icon"></BaseIcon>
                </div>
                <div class="ml-3 truncate">{{ item.label }}</div>
                <div class="ml-auto w-48 truncate text-slate-500 text-xs flex justify-end items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                       stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                       class="lucide w-3.5 h-3.5 mr-2">
                    <path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"></path>
                    <path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"></path>
                  </svg>
                  Quick Access
                </div>
              </a>

            </div>
          </template>
        </AsyncData>
      </template>

    </div>
  </a-modal>
</template>

<script>
import {defineComponent, ref, watch} from "vue";
import {useMagicKeys, useFocus} from "@vueuse/core";
import {SearchOutlined} from "@ant-design/icons-vue";
import SearchItems from "./SearchItems";
import {AsyncData, BaseIcon} from "@/components";

export default defineComponent({
  name: 'AppSearch',
  components: {SearchOutlined, BaseIcon, AsyncData},
  setup() {
    const showModal = ref(false);
    const searchInput = ref();
    const term = ref();
    const {focused} = useFocus(searchInput)
    const {ctrl_k} = useMagicKeys({
      passive: false,
      onEventFired(e) {
        if (e.ctrlKey && e.key === 'k' && e.type === 'keydown')
          e.preventDefault()
      },
    })

    watch(ctrl_k, (v) => {
      if (v)
        showModal.value = !showModal.value
    })

    function changeModal(show) {
      showModal.value = show;
      setTimeout(() => {
        focused.value = true
      }, 500)
    }

    return {
      doAction(action) {
        showModal.value = false
        action()
      },
      focused,
      term,
      SearchItems,
      showModal,
      searchInput,
      changeModal
    }
  }
})
</script>
