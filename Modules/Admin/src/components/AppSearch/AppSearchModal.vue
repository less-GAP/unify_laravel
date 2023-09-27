<template>
  <a-modal :closable="false" v-model:open="showModal">
    <a-space>
      <div
        class="h-6 text-xs bg-slate-200 text-slate-500 px-2 flex items-center rounded-md  my-auto ">
        Up
      </div>
      <div
        class="h-6 text-xs bg-slate-200 text-slate-500 px-2 flex items-center rounded-md  my-auto ">
        Down
      </div>

      <div
        class="h-6 text-xs bg-slate-200 text-slate-500 px-2 flex items-center rounded-md  my-auto ">
        Tab
      </div>
      <div
        class="h-6 text-xs bg-slate-200 text-slate-500 px-2 flex items-center rounded-md  my-auto">
        Enter
      </div>
      <div
        class="h-6 text-xs bg-slate-200 text-slate-500 px-2 flex items-center rounded-md  my-auto">
      ESC
      </div>
    </a-space>
    <div class="relative border-b border-slate-200/60">
      <a-input autofocus v-model:value="term" allow-clear ref="searchInput" type="search"
             class="form-control border-0 shadow-none focus:ring-0 !mt-5 px-12"
             placeholder="Quick Search...">
        <template #prefix>
          <SearchOutlined></SearchOutlined>
        </template>
      </a-input>

    </div>
    <div :key="term" class="py-5">
      <a-space :size="[0, 8]" wrap>
        <a-checkable-tag
          v-for="(tag, index) in SearchList"
          :key="index"
          :checked="selectedSearch == index"
          @click="selectedSearch = index"
        >
          <span  class="font-xl ">{{ tag.label }}</span>
        </a-checkable-tag>
      </a-space>
      <div :key="selectedSearch+term" >
        <AsyncData @load="data=>items=data" v-if="$auth.hasPermission(SearchItem.permission) && SearchItem" :async="SearchItem.search(term)">
          <template #default="{data}">
            <div v-if="data.length" class="mt-5 mb-3">
              <a @click="doAction(item.action)" v-for="(item,itemIndex) in data"
                 :class="selectedItem==itemIndex?'!bg-gray-200':''"
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
      </div>

    </div>
    <template #footer></template>
  </a-modal>
</template>

<script>
import {computed, defineComponent, ref, watch} from "vue";
import {useMagicKeys, useFocus} from "@vueuse/core";
import {SearchOutlined} from "@ant-design/icons-vue";
import SearchItems from "./SearchItems";
import {AsyncData, BaseIcon} from "@/components";
import {useAuthStore} from "@/stores/auth";
export default defineComponent({
  name: 'AppSearch',
  components: {SearchOutlined, BaseIcon, AsyncData},
  setup() {
    const auth = useAuthStore();
    const showModal = ref(false);
    const selectedSearch = ref(0);
    const selectedItem = ref(0);
    const searchInput = ref(null);
    const items = ref([]);
    const term = ref();
    const SearchList = SearchItems.filter(SearchItem=>{
       return auth.hasPermission(SearchItem.permission)
    })
    const SearchItem = computed(()=>{
      return SearchList[selectedSearch.value]
    });
    const {ctrl_k,ArrowUp,ArrowDown,Tab,Enter} = useMagicKeys({
      passive: false,
      onEventFired(e) {
        if (e.ctrlKey && e.key === 'k' && e.type === 'keydown')
          e.preventDefault()
      },
    })

    watch(Enter, (v) => {
      if(v)
       items.value[selectedItem.value]?.action()
    })
    watch(ArrowUp, (v) => {
      if(v)
        selectedItem.value =  selectedItem.value>0?selectedItem.value-1:items.value.length-1
    })

    watch(Tab, (v) => {
      if(v)
        selectedSearch.value =  (selectedSearch.value==SearchList.length-1)? 0:selectedSearch.value+1
    })
    watch(ArrowDown, (v) => {
      if(v)
        selectedItem.value = (selectedItem.value==items.value.length-1)? 0:selectedItem.value+1
    })

    watch(ctrl_k, (v) => {
      if (v)
        changeModal(true)
    })

    function changeModal(show) {
      showModal.value = show;
      if(show){
        setTimeout(()=>{
          searchInput.value.focus()
        },200)
      }
    }

    return {
      doAction(action) {
        showModal.value = false
        action()
      },
      items,
      selectedSearch,
      SearchItem,
      selectedItem,
      term,
      SearchList,
      showModal,
      searchInput,
      changeModal
    }
  }
})
</script>
