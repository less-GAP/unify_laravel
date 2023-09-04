<script setup>
import { reactive, h, ref, toRaw, computed } from "vue";
import { mdiBackspace, mdiContentSave } from '@mdi/js';
import {BaseIcon} from "@/components";
import router from "@/router";
import { useAuthStore } from "@/stores/auth";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import dayjs from 'dayjs';
import { listProcess, getProcess } from "@/utils/Process";

const listProcessOptions = listProcess();
const prefix = 'patient'
const {
    fetchDetailApi,
    deleteApi,
    updateApi,
} = UseEloquentRouter(prefix);

defineProps({
    value: {
        type: Object,
        default: {}
    }, visible: {
        type: Boolean,
        default: true
    },
});
const formState = reactive({});
const formRef = ref();
const loading = ref(false);
const auth = useAuthStore();

const customFormat = 'MM-DD-YYYY';
const dbFormat = "YYYY-MM-DD";
const age = (formState) => {
  return formState.dob ? '(' + dayjs().diff(dayjs(formState.dob, dbFormat), 'year') + ' years old)' : '';
};

const fetch = async function () {
    loading.value = true;
    var id = router.currentRoute.value.params.id;
    if (id !== 'new') {
        loading.value = true
        const value = await fetchDetailApi(id)
        Object.assign(formState, value.data)
        loading.value = false
    } else {
        loading.value = false
    }
}
fetch();
const submit = () => {
    formRef.value
        .validate()
        .then(() => {
            updateApi( formState.id, {  ...formState }).then(rs => {
                Object.assign(formState, rs.data.result)
            });
        })
};
const closeDetail = function () {
    router.replace({ path: '/' + prefix })
};
</script>
<template>
    <a-drawer :closable="false" style="position:relative;display:flex;flex-direction:column;height:100vh;"
    @close="closeDetail" :open="visible" width="50vw">
        <a-form layout="vertical" v-bind="$config.formConfig" ref="formRef" :model="formState" @finish="submit">
            <div class="bg-gray-200 p-3">
                <a-button class="!hidden md:!inline-block" type="link" @click="closeDetail">
                    <template #icon>
                        <div class="flex">
                            <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500" />
                            <span class="text-stone-500 ml-1">Back</span>
                        </div>
                    </template>
                </a-button>
                <a-button class="!inline-flex items-center justify-center md:!hidden !w-8 !h-8 !p-0" type="link"
                    @click="closeDetail">
                    <template #icon>
                        <div class="flex">
                            <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500" />
                            <span class="text-stone-500 ml-1">Back</span>
                        </div>
                    </template>
                </a-button>
                <a-space class="float-right">
                    <!-- <a-button @click="submit()" :loading="loading" type="primary">Update</a-button> -->
                </a-space>
            </div>
            <div class="px-4 mt-5 overflow-y-auto" style="height:calc(100% - 60px)">
                <div class="flex flex-wrap -mx-4">
                    <h1 class="w-full px-4 flex flex-col">
                        <div class="flex items-center leading-none whitespace-nowrap">
                            <span>{{ formState.full_name }}</span>
                        </div>
                        <div class="text-gray-400 text-sm">#{{ formState.unify_number }}</div>
                    </h1>
                    <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="1rem" plain>List tasks of {{ formState.full_name }}</a-Divider>
                    <div class="w-full px-4">
                      
                        <div class="flex items-center font-medium">
                            <div class="flex flex-grow items-center text-gray-900">
                                <!-- Component Start -->
                                <div class="p-8 bg-yellow-100 rounded-xl border-red-400 w-full">
                                    <div class="flex items-center mb-6">
                                        <svg class="h-8 w-8 text-indigo-500 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <h4 class="font-semibold ml-3 text-lg">Need to do</h4>
                                    </div>
                                    <div>
                                        <input class="hidden" type="checkbox" id="task_1" checked>
                                        <label class="flex items-center h-10 px-2 rounded-lg cursor-pointer hover:bg-white" for="task_1">
                                            <span class="flex items-center justify-center w-5 h-5 text-transparent border-2 border-gray-900 rounded-full">
                                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                            <span class="ml-4 text-sm">Weed front garden.</span>
                                        </label>	
                                    </div>
                                    <div>
                                        <input class="hidden" type="checkbox" id="task_2" checked>
                                        <label class="flex items-center h-10 px-2 rounded-lg cursor-pointer hover:bg-white" for="task_2">
                                            <span class="flex items-center justify-center w-5 h-5 text-transparent border-2 border-gray-900 rounded-full">
                                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                            <span class="ml-4 text-sm">Chill and smoke some Old Toby.</span>
                                        </label>	
                                    </div>
                                    <div>
                                        <input class="hidden" type="checkbox" id="task_3">
                                        <label class="flex items-center h-10 px-2 rounded-lg cursor-pointer hover:bg-white" for="task_3">
                                            <span class="flex items-center justify-center w-5 h-5 text-transparent border-2 border-gray-900 rounded-full">
                                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                            <span class="ml-4 text-sm">Keep ring secret and safe.</span>
                                        </label>	
                                    </div>
                                    <div>
                                        <input class="hidden" type="checkbox" id="task_4">
                                        <label class="flex items-center h-10 px-2 rounded-lg cursor-pointer hover:bg-white" for="task_4">
                                            <span class="flex items-center justify-center w-5 h-5 text-transparent border-2 border-gray-900 rounded-full">
                                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                            <span class="ml-4 text-sm">Meet Gandalf at Bree.</span>
                                        </label>	
                                    </div>
                                    <div>
                                        <input class="hidden" type="checkbox" id="task_5">
                                        <label class="flex items-center h-10 px-2 rounded-lg cursor-pointer hover:bg-white" for="task_5">
                                            <span class="flex items-center justify-center w-5 h-5 text-transparent border-2 border-gray-900 rounded-full">
                                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                            <span class="ml-4 text-sm">Destroy ring and defeat dark lord.</span>
                                        </label>	
                                    </div>
                                </div>
                                <!-- Component End  -->
                            </div>
                        </div>



                    </div>
                    <div class="w-full px-4 bg-gray-100 pb-3 mt-5">
                        
                        <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="0" plain>Recent history</a-Divider>
                        <div class="relative flex flex-col items-start p-4 mt-3 bg-white rounded-lg cursor-pointer bg-opacity-90 group hover:bg-opacity-100" draggable="true">
                            <button class="absolute top-0 right-0 flex items-center justify-center hidden w-5 h-5 mt-3 mr-2 text-gray-500 rounded hover:bg-gray-200 hover:text-gray-700 group-hover:flex">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                </svg>
                            </button>
                            <span class="flex items-center h-6 px-3 text-xs font-semibold text-green-500 bg-green-100 rounded-full">Dev</span>
                            <h4 class="mt-3 text-sm font-medium">This is the title of the card for the thing that needs to be done.</h4>
                            <div class="flex items-center w-full mt-3 text-xs font-medium text-gray-400">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-1 leading-none">Dec 12</span>
                                </div>
                                <div class="relative flex items-center ml-4">
                                    <svg class="relative w-4 h-4 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-1 leading-none">4</span>
                                </div>
                                <div class="flex items-center ml-4">
                                    <svg class="w-4 h-4 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-1 leading-none">1</span>
                                </div>
                                <img class="w-6 h-6 ml-auto rounded-full" src="https://randomuser.me/api/portraits/men/64.jpg">
                            </div>
                        </div>
                        <div class="relative flex flex-col items-start p-4 mt-3 bg-white rounded-lg cursor-pointer bg-opacity-90 group hover:bg-opacity-100" draggable="true">
                            <button class="absolute top-0 right-0 flex items-center justify-center hidden w-5 h-5 mt-3 mr-2 text-gray-500 rounded hover:bg-gray-200 hover:text-gray-700 group-hover:flex">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                </svg>
                            </button>
                            <span class="flex items-center h-6 px-3 text-xs font-semibold text-green-500 bg-green-100 rounded-full">Dev</span>
                            <h4 class="mt-3 text-sm font-medium">This is the title of the card for the thing that needs to be done.</h4>
                            <div class="flex items-center w-full mt-3 text-xs font-medium text-gray-400">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-1 leading-none">Dec 12</span>
                                </div>
                                <div class="relative flex items-center ml-4">
                                    <svg class="relative w-4 h-4 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-1 leading-none">4</span>
                                </div>
                                <div class="flex items-center ml-4">
                                    <svg class="w-4 h-4 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-1 leading-none">1</span>
                                </div>
                                <img class="w-6 h-6 ml-auto rounded-full" src="https://randomuser.me/api/portraits/men/64.jpg">
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </a-form>
    </a-drawer>
</template>

<style>
.ant-drawer-body {
  padding: 0 !important
}
</style>
