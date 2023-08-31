<script lang="ts" setup>
import { ref, computed, reactive } from "vue";
import SignaturePanel from "./SignaturePanel.vue";
import Api from "@/utils/Api";
import { useAuthStore } from "@/stores/auth";

import {
    fetchListStatesApi,
    fetchListInsurancesApi,
} from "../patients/meta";

const listStates = fetchListStatesApi();
const listInsurances = fetchListInsurancesApi();
const genderList = [
  {
    value: 0,
    label: 'Male'
  },
  {
    value: 1,
    label: 'Female'
  }
];
const formState = reactive({});
const formRef = ref({
    full_name: "",
    first_name: "",
    last_name: "",
    doctor_name: "",
    weight: "",
    height: "",
    phone: "",
    dob: "",
    gender: "",
    email: "",
    address: "",
    apt: "",
    zip: "",
    city: "",
    state: "",
    signature: "",
    note: "",
});

const showInsurance = (e) => {
    if (e.target.checked) {
        document.getElementById("insurance_info").classList.remove("hidden");
    } else {
        document.getElementById("insurance_info").classList.add("hidden");
    }
};
const authStore = useAuthStore();
const saler_id = computed(() => {
    return authStore.user ? authStore.user.id : 0;
});

const prefix = 'patient'
const createApi = function (params) {
    return Api.post(prefix, params)
};

const submit = async () => {
    try {
        await formRef.value.validate();

        // Combine first_name and last_name to create full_name
        formState.full_name = formState.first_name + ' ' + formState.last_name;

        // Submit the form data
        const response = await createApi({ ...formState });

        // Update formState with response data
        Object.assign(formState, response.data.result);
    } catch (e) {
        alert('Something went wrong!');
    }
};
</script>

<template>
    <div class="w-full max-h-screen overflow-y-auto uni-main-container">
        <div class="w-full mx-auto">
            <div class="">
                <a-typography-title class="uni-heading -indent-[9999px] absolute m-0 p-0">Unifymed</a-typography-title>
                <div class="flex items-center justify-center px-3 my-3">
                    <img src="@/assets/logo.png" alt="Unifymed" class="block w-28">
                </div>
            </div>
            <a-form layout="vertical" ref="formRef" :model="formState" @finish="submit">
                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="first_name"
                            class="inline-block mb-1 text-sm font-medium text-gray-600">Firstname</label>
                        <a-input type="text" name="first_name" id="first_name" v-model:value="formState.first_name"
                            class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            required></a-input>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="last_name" class="inline-block mb-1 text-sm font-medium text-gray-600">Lastname</label>
                        <a-input type="text" name="last_name" id="last_name" v-model:value="formState.last_name"
                            class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            required></a-input>
                    </div>
                </div>

                <div @v-if="insuranceCoverages" class="relative z-0 w-full mb-6 group">
                    <label class="relative inline-flex items-center mb-4 cursor-pointer" for="insurance">
                        <div class="relative flex items-center">
                            <input v-on:change="showInsurance" type="checkbox" name="insurance" id="insurance"
                                class="sr-only peer focus:outline-none">
                            <div
                                class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                            </div>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-900">Do you already have health insurance?</span>
                    </label>
                    <div class="relative hidden w-full" id="insurance_info">
                        <div class="relative z-0 w-full mb-6 group">
                            <label class="inline-block mb-1 text-sm font-medium text-gray-600">Choose the insurance plan you
                                have joined:</label>
                            <a-checkbox-group name="insurance_coverages" :options="listInsurances" v-model:value="formState.insurance_coverages"
                                class="block"></a-checkbox-group>
                        </div>
                    </div>
                </div>
                <div class="grid md:grid-cols-3 md:gap-6">
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="doctor_name" class="inline-block mb-1 text-sm font-medium text-gray-600">Doctor
                            Name</label>
                        <a-input type="text" name="doctor_name" id="doctor_name" v-model:value="formState.doctor_name"
                            class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"></a-input>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="weight" class="inline-block mb-1 text-sm font-medium text-gray-600">Weight</label>
                        <a-input type="text" name="weight" id="weight" v-model:value="formState.weight"
                            class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"></a-input>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="height" class="inline-block mb-1 text-sm font-medium text-gray-600">Height</label>
                        <a-input type="text" name="height" id="height" v-model:value="formState.height"
                            class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"></a-input>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="phone" class="inline-block mb-1 text-sm font-medium text-gray-600">Phone</label>
                        <a-input type="text" name="phone" id="phone" required v-model:value="formState.phone"
                            class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"></a-input>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="dob" class="inline-block mb-1 text-sm font-medium text-gray-600">Date of Birth</label>
                        <a-date-picker v-model:value="formState.dob" required inputReadOnly name="dob" id="dob"
                            valueFormat="YYYY-MM-DD" format="MM-DD-YYYY"
                            class="block date-picker w-full px-0 py-1 text-base font-bold text-gray-900 uppercase !bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"></a-date-picker>
                    </div>
                </div>
                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="gender" class="inline-block mb-1 text-sm font-medium text-gray-600">Gender</label>
                        <a-select v-model:value="formState.gender"
                            class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            name="gender" id="gender" required placeholder="Select your gender"
                            :options="genderList"
                            >
                        </a-select>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="email" class="inline-block mb-1 text-sm font-medium text-gray-600">Email</label>
                        <a-input type="email" name="email" id="email" required v-model:value="formState.email"
                            class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"></a-input>
                    </div>
                </div>

                <div class="relative z-0 w-full mb-6 group">
                    <label for="address" class="inline-block mb-1 text-sm font-medium text-gray-600">Address</label>
                    <a-input type="text" name="street" id="address" v-model:value="formState.street"
                        class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"></a-input>
                </div>
                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="apt" class="inline-block mb-1 text-sm font-medium text-gray-600">Apt</label>
                        <a-input type="text" name="apt" id="apt" v-model:value="formState.atp"
                            class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"></a-input>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="zip" class="inline-block mb-1 text-sm font-medium text-gray-600">ZIP</label>
                        <a-input type="text" name="zip" id="zip" v-model:value="formState.zip"
                            class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"></a-input>
                    </div>
                </div>
                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="city" class="inline-block mb-1 text-sm font-medium text-gray-600">City</label>
                        <a-input type="text" name="city" id="city" v-model:value="formState.city"
                            class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"></a-input>
                    </div>
                    <div class="relative z-0 w-full mb-6 group" v-if="listStates">
                        <label for="state" class="inline-block mb-1 text-sm font-medium text-gray-600">State</label>
                        <div class="relative w-full">
                            <div class="relative w-full">
                                <a-select showSearch name="state" placeholder="Select a state" id="s_state"
                                    v-model:value="formState.state"
                                    class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                    <a-select-option v-for="state in listStates" :key="state.code" :value="state.code">{{
                                        state.name
                                    }} ({{ state.code }})</a-select-option>
                                </a-select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative z-0 w-full mb-3 group">
                    <label class="text-sm text-gray-500 duration-300 -translate-y-6 -z-10">Signature</label>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <SignaturePanel></SignaturePanel>
                    <button id="clear-signature"
                        class="absolute z-20 px-2 py-1 text-sm text-white bg-gray-400 border-0 rounded-sm top-2 left-2">
                        Clear
                    </button>
                    <a-input type="file" name="uploadSignature" id="uploadSignature" class="!hidden"></a-input>
                </div>

                <div class="relative z-0 w-full mb-3 group">
                    <label for="note" class="text-sm text-gray-500 duration-300 -translate-y-6 -z-10">Your request</label>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <a-textarea v-model:value="formState.note" class="!rounded-none" name="note" id="note" :auto-size="{ minRows: 2, maxRows: 5 }" />
                </div>

                <button id="submitForm" type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    Submit
                </button>

                <a-input type="hidden" name="unify_process" value="0"></a-input>
                <a-input type="hidden" name="sale_user" v-model:value="saler_id"></a-input>
            </a-form>
        </div>
    </div>
</template>

<style scoped></style>
