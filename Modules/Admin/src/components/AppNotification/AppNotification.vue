<script>
import {defineComponent, watch, ref, unref} from 'vue';
import {SearchOutlined} from '@ant-design/icons-vue';
import {db} from "@/utils/RealtimeDB";
import {useAuthStore} from "@/stores/auth";
import Api from "@/utils/Api";
import {ApiData} from "@/components";
import {useAppStateStore} from "@/stores/appState";

export default defineComponent({
  name: 'AppSearch',
  components: {SearchOutlined, ApiData},
  setup() {
    const modal = ref()
    const items = ref([])
    const unreadCount = ref(0)
    const appState = useAppStateStore()
    const changeModal = function () {
      modal.value.changeModal(true)
    }

    const fetch = async function () {
      const res = await Api.get('/notifications');
      items.value = res.data.items
      unreadCount.value = res.data.unread_count
    }
    const getUrl = function (item) {
      if (item.data?.model_class == "App\\Models\\Patient") {
        return '/patient/' + item.data.model?.id + '/detail'
      }
      return '';
    }
    watch(() => appState.versions['notification'], () => {
      fetch()
    })
    fetch()
    return {
      getUrl,
      async read(item) {
        if (item.read_at) {
          return true;
        }
        await Api.post('notification/read/', item)
        fetch()
      },
      items,
      unreadCount,
      modal,
      changeModal
    }
  }
})
</script>
<template>
  <button type="button" data-dropdown-toggle="notification-dropdown"
          class="p-2 text-gray-500 relative rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700">
    <span class="sr-only">View notifications</span>

    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path
        d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z">
      </path>
    </svg>
    <div
      v-if="unreadCount"
      class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -right-2 dark:border-gray-900">
      {{ unreadCount }}
    </div>

  </button>
  <div
    class="z-20 z-50 hidden max-w-sm my-4 overflow-hidden text-base list-none bg-white divide-y divide-gray-100 rounded shadow-lg dark:divide-gray-600 dark:bg-gray-700"
    id="notification-dropdown" data-popper-placement="bottom"
    style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(1048px, 65px);">
    <div
      class="block px-4 py-2 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
      Notifications
    </div>
    <router-link @click="read(item)" :to="getUrl(item)" :key="item?.id" v-for="item in items"
                 :class="!item?.read_at?'bg-gray-200':''"
                 class="flex px-4 py-3 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600">
      <div class="flex-shrink-0">
        <img class="rounded-full w-11 h-11"
             src="https://flowbite-admin-dashboard.vercel.app/images/users/bonnie-green.png" alt="Jese image">
        <div
          class="absolute flex items-center justify-center w-5 h-5 ml-6 -mt-5 border border-white rounded-full bg-primary-700 dark:border-gray-700">
          <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"
               xmlns="http://www.w3.org/2000/svg">
            <path
              d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z">
            </path>
            <path
              d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z">
            </path>
          </svg>
        </div>
      </div>
      <div class="w-full pl-3">
        <div class="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400">{{ item.data.title }} <span
          class="font-semibold text-gray-900 dark:text-white">
          </span>
        </div>
        <div class="text-xs font-medium text-primary-700 dark:text-primary-400">
          {{ $format.diffHuman(item.created_at) }}
        </div>
      </div>
    </router-link>


    <!--    <a href="#"-->
    <!--       class="block py-2 text-base font-normal text-center text-gray-900 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:hover:underline">-->
    <!--      <div class="inline-flex items-center ">-->
    <!--        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">-->
    <!--          <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>-->
    <!--          <path fill-rule="evenodd"-->
    <!--                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"-->
    <!--                clip-rule="evenodd"></path>-->
    <!--        </svg>-->
    <!--        View all-->
    <!--      </div>-->
    <!--    </a>-->
  </div>
</template>
