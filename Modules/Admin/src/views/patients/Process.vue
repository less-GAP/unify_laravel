<script setup>
import { reactive, h, ref, toRaw, computed } from "vue";
import { mdiBackspace, mdiContentSave } from '@mdi/js';
import { notification } from "ant-design-vue";
import { BaseIcon } from "@/components";
import router from "@/router";
import { useAuthStore } from "@/stores/auth";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import dayjs from 'dayjs';
import { listProcess, useNeedToDoList } from "@/utils/Patient";


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
const formState = reactive({})
const formRef = ref();
const loading = ref(false);
const auth = useAuthStore();

const needToDoLib = useNeedToDoList();
var needToDoList = [];
const customFormat = 'MM-DD-YYYY';
const dbFormat = "YYYY-MM-DD";
const age = (formState) => {
    return formState.dob ? '(' + dayjs().diff(dayjs(formState.dob, dbFormat), 'year') + ' years old)' : '';
};
const openNotificationWithIcon = (type) => {
    notification[type]({
        message: 'Warning: Need to do',
        description: 'Please check list need to do on Task tab and complete it before Approve new patient',
    });
};
const fetch = async function () {
    loading.value = true;
    var id = router.currentRoute.value.params.id;
    if (id !== 'new') {
        loading.value = true
        const value = await fetchDetailApi(id)
        Object.assign(formState, value.data.data)
        needToDoLib.forEach((item) => {
            if (formState[item.key] != null && formState[item.key] !== 0) {
                return;
            }
            if (item.key == 'doctor_id' && formState[item.key] !== null) {
                return;
            }
            needToDoList.push({
                value: item.key,
                label: item.noti,
            })
        })
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
            if (needToDoList.length > 0 && formState.unify_process == 2) {
                openNotificationWithIcon('warning') // check need to do before approve
                return false;
            }
            if (formState.unify_process == 2) {
                formState.unify_status = 1
            }
            updateApi(formState.id, { ...formState }).then(rs => {
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
        @close="closeDetail" :open="visible">
        <a-form layout="vertical" v-bind="$config.formConfig" ref="formRef" :model="formState" @finish="submit">
            <div class="p-3 bg-gray-200">
                <a-button class="!hidden md:!inline-block" type="link" @click="closeDetail">
                    <template #icon>
                        <div class="flex">
                            <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500" />
                            <span class="ml-1 text-stone-500">Back</span>
                        </div>
                    </template>
                </a-button>
                <a-button class="!inline-flex items-center justify-center md:!hidden !w-8 !h-8 !p-0" type="link"
                    @click="closeDetail">
                    <template #icon>
                        <div class="flex">
                            <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500" />
                            <span class="ml-1 text-stone-500">Back</span>
                        </div>
                    </template>
                </a-button>
                <a-space class="float-right">
                    <a-button @click="submit()" :loading="loading" type="primary">Update</a-button>
                </a-space>
            </div>
            <div class="px-4 mt-5 overflow-y-auto" style="height:calc(100% - 60px)">
                <div class="flex flex-wrap -mx-4">
                    <h3 class="block w-full px-4 mb-4 leading-6"><strong>{{ formState.full_name }}</strong> <span
                            class="text-xs leading-6">{{ age(formState) }}</span></h3>
                    <div class="w-full px-4">
                        <a-form-item required v-if="auth.user.roles.find(x => x.name === 'Admin') !== false"
                            label="Choose process" name="unify_process">
                            <a-select class="w-full" v-model:value="formState.unify_process"
                                :options="listProcessOptions"></a-select>
                        </a-form-item>
                        <a-form-item required label="Publish date" v-if="formState.unify_process == 2">
                            <a-date-picker class="w-full" :showTime="{ format: 'HH:mm' }" inputReadOnly
                                v-model:value="formState.unify_active" valueFormat="YYYY-MM-DD HH:mm:ss"
                                format="HH:mm MM-DD-YYYY" :disabled="formState.unify_process != 2"></a-date-picker>
                        </a-form-item>
                        <a-form-item required label="Notes for this change" name="log_detail">
                            <a-textarea class="w-full" v-model:value="formState.log_detail" :rows="4"
                                :auto-size="{ minRows: 4, maxRows: 8 }"></a-textarea>
                        </a-form-item>
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
