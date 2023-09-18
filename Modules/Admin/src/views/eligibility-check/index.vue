<script lang="ts" setup>
import { ref, computed, reactive, toRaw } from "vue";
import SignaturePanel from "./SignaturePanel.vue";
import { useAuthStore } from "@/stores/auth";
import router from "@/router";
import Api from "@/utils/Api";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import { fetchListInsurancesApi } from "@/utils/Patient";
import listStates from "@/utils/States";

const configNames = ["default_seller"];
const defaultSeller = ref();

const listInsurances = fetchListInsurancesApi();
const genderList = [
  {
    value: 0,
    label: "Male",
  },
  {
    value: 1,
    label: "Female",
  },
];
const formState = reactive({});
const formRef = ref();

const showInsurance = (e) => {
  if (e.target.checked) {
    document.getElementById("insurance_info").classList.remove("hidden");
  } else {
    document.getElementById("insurance_info").classList.add("hidden");
  }
};
const authStore = useAuthStore();

const prefix = "patient";
const signaturePanel = ref();
const { createApi } = UseEloquentRouter(prefix);

Api.get("config", { params: { names: configNames } }).then((result) => {
  defaultSeller.value = result.data.default_seller || 0; // O is default admin id
});

const saler_id = computed(() => {
  return authStore.user ? authStore.user.id : defaultSeller.value;
});

const submit = () => {
  // Combine first_name and last_name to create full_name
  formState.full_name = formState.first_name + " " + formState.last_name;
  if (formState.weight !== undefined) {
    formState.unify_data += "Weight: " + formState.weight;
  }
  if (formState.height !== undefined) {
    formState.unify_data += "\nHeight: " + formState.height;
  }
  if (formState.doctor_name !== undefined) {
    formState.unify_data += "\nDoctor Name: " + formState.doctor_name;
  }
  var insurance_coverages = [];
  if (formState.insurance_coverages) {
    formState.insurance_coverages.forEach((element, index) => {
      insurance_coverages.push({
        coverage: element,
        insurance_id: null,
        active_date: null,
        expired_date: null,
      });
    });
  }
  if (insurance_coverages.length) {
    formState.insurance_coverages = JSON.stringify(toRaw(insurance_coverages));
  } else {
    formState.insurance_coverages = null;
  }

  formState.unify_process = 0;
  formState.sale_user = saler_id.value;
  formState.signature = signaturePanel.value.save("image/png");

  // Submit the form data
  Api.post("submit-patient", { ...formState }).then((rs) => {
    Object.assign(formState, rs.data.result);
    router.replace("thank-you");
  });
};
</script>

<template>
  <div class="w-full max-h-screen overflow-y-auto uni-main-container">
    <div class="w-full mx-auto">
      <div class="">
        <a-typography-title
          class="uni-heading -indent-[9999px] absolute m-0 p-0"
          >Unifymed</a-typography-title
        >
        <div class="flex items-center justify-center px-3 my-3">
          <img src="@/assets/logo.png" alt="Unifymed" class="block w-28" />
        </div>
      </div>
      <a-form
        ref="formRef"
        layout="vertical"
        :model="formState"
        @finish="submit"
      >
        <div class="grid md:grid-cols-2 md:gap-6">
          <div class="relative z-0 w-full mb-6 group">
            <a-form-item
              label="First Name"
              name="first_name"
              :rules="[{ required: true, message: 'Please enter first name!' }]"
            >
              <a-input
                v-model:value="formState.first_name"
                class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
              ></a-input>
            </a-form-item>
          </div>
          <div class="relative z-0 w-full mb-6 group">
            <a-form-item
              label="Last Name"
              name="last_name"
              :rules="[{ required: true, message: 'Please enter last name!' }]"
            >
              <a-input
                v-model:value="formState.last_name"
                class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
              ></a-input>
            </a-form-item>
          </div>
        </div>

        <div class="relative z-0 w-full mb-6 group" @v-if="listInsurances">
          <label
            class="relative inline-flex items-center mb-4 cursor-pointer"
            for="insurance"
          >
            <div class="relative flex items-center">
              <input
                id="insurance"
                type="checkbox"
                name="insurance"
                class="sr-only peer focus:outline-none"
                @change="showInsurance"
              />
              <div
                class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"
              ></div>
            </div>
            <span class="ml-3 text-sm font-medium text-gray-900"
              >Do you already have health insurance?</span
            >
          </label>
          <div id="insurance_info" class="relative hidden w-full">
            <div class="relative z-0 w-full mb-6 group">
              <label class="block w-full mb-1 text-sm font-medium text-gray-600"
                >Choose the insurance plan you have joined:</label
              >
              <a-checkbox-group
                v-model:value="formState.insurance_coverages"
                :options="listInsurances"
                class="block"
              ></a-checkbox-group>
            </div>
          </div>
        </div>
        <div class="grid md:grid-cols-3 md:gap-6">
          <div class="relative z-0 w-full mb-6 group">
            <a-form-item label="Doctor Name">
              <a-input
                v-model:value="formState.doctor_name"
                class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
              ></a-input>
            </a-form-item>
          </div>
          <div class="relative z-0 w-full mb-6 group">
            <a-form-item label="Weight">
              <a-input
                v-model:value="formState.weight"
                class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
              ></a-input>
            </a-form-item>
          </div>
          <div class="relative z-0 w-full mb-6 group">
            <a-form-item label="Height">
              <a-input
                v-model:value="formState.height"
                class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
              ></a-input>
            </a-form-item>
          </div>
        </div>

        <div class="grid md:grid-cols-2 md:gap-6">
          <div class="relative z-0 w-full mb-6 group">
            <a-form-item
              label="Phone"
              name="phone"
              :rules="[{ required: true, message: 'Please enter phone!' }]"
            >
              <a-input
                v-model:value="formState.phone"
                class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
              ></a-input>
            </a-form-item>
          </div>
          <div class="relative z-0 w-full mb-6 group">
            <a-form-item
              label="Date of Birth"
              :rules="[
                {
                  required: true,
                  message: 'Please select your date of birth!',
                },
              ]"
            >
              <a-date-picker
                v-model:value="formState.dob"
                input-read-only
                value-format="YYYY-MM-DD"
                format="MM-DD-YYYY"
                class="block date-picker w-full px-0 py-1 text-base font-bold text-gray-900 uppercase !bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
              ></a-date-picker>
            </a-form-item>
          </div>
        </div>
        <div class="grid md:grid-cols-2 md:gap-6">
          <div class="relative z-0 w-full mb-6 group">
            <a-form-item
              label="Gender"
              name="gender"
              :rules="[{ required: true, message: 'Please enter gender!' }]"
            >
              <a-select
                v-model:value="formState.gender"
                class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder="Select your gender"
                :options="genderList"
              >
              </a-select>
            </a-form-item>
          </div>
          <div class="relative z-0 w-full mb-6 group">
            <a-form-item
              label="Email"
              name="email"
              :rules="[
                {
                  type: 'email',
                  message: 'The input is not valid email!',
                },
              ]"
            >
              <a-input
                v-model:value="formState.email"
                type="email"
                class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
              ></a-input>
            </a-form-item>
          </div>
        </div>

        <div class="relative z-0 w-full mb-6 group">
          <a-form-item
            label="Address"
            name="address"
            :rules="[{ required: true, message: 'Please enter address!' }]"
          >
            <a-input
              id="address"
              v-model:value="formState.address"
              name="street"
              class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
            ></a-input>
          </a-form-item>
        </div>
        <div class="grid md:grid-cols-2 md:gap-6">
          <div class="relative z-0 w-full mb-6 group">
            <label
              for="apt"
              class="inline-block mb-1 text-sm font-medium text-gray-600"
              >Apt</label
            >
            <a-input
              id="apt"
              v-model:value="formState.atp"
              name="apt"
              class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
            ></a-input>
          </div>
          <div class="relative z-0 w-full mb-6 group">
            <label
              for="zip"
              class="inline-block mb-1 text-sm font-medium text-gray-600"
              >ZIP</label
            >
            <a-input
              id="zip"
              v-model:value="formState.zip"
              name="zip"
              class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
            ></a-input>
          </div>
        </div>
        <div class="grid md:grid-cols-2 md:gap-6">
          <div class="relative z-0 w-full mb-6 group">
            <a-form-item
              label="City"
              name="city"
              :rules="[{ required: true, message: 'Please enter city!' }]"
            >
              <a-input
                id="city"
                v-model:value="formState.city"
                name="city"
                class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
              ></a-input>
            </a-form-item>
          </div>
          <div v-if="listStates" class="relative z-0 w-full mb-6 group">
            <a-form-item
              label="State"
              name="state"
              :rules="[{ required: true, message: 'Please select state!' }]"
            >
              <a-select
                id="s_state"
                v-model:value="formState.state"
                show-search
                name="state"
                placeholder="Select a state"
                class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
              >
                <a-select-option
                  v-for="state in listStates"
                  :key="state.value"
                  :value="state.label"
                  >{{ state.label }} ({{ state.value }})
                </a-select-option>
              </a-select>
            </a-form-item>
          </div>
        </div>

        <div class="relative z-0 w-full mb-3 group">
          <div class="ant-form-item">
            <div class="ant-col ant-form-item-label">
              <label class="ant-form-item-required" title="Signature"
                >Signature</label
              >
            </div>
          </div>
          <SignaturePanel ref="signaturePanel"></SignaturePanel>
          <a-input
            id="uploadSignature"
            type="file"
            name="uploadSignature"
            class="!hidden"
          ></a-input>
        </div>

        <div class="relative z-0 w-full mb-3 group">
          <label
            for="note"
            class="text-sm text-gray-500 duration-300 -translate-y-6 -z-10"
            >Your request</label
          >
        </div>
        <div class="relative z-0 w-full mb-6 group">
          <a-textarea
            v-model:value="formState.note"
            class="!rounded-none"
            :auto-size="{ minRows: 2, maxRows: 5 }"
          />
        </div>

        <button
          id="submitForm"
          type="submit"
          class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
        >
          Submit
        </button>
      </a-form>
    </div>
  </div>
</template>

<style>
.ant-form .ant-form-item .ant-form-item-label {
  padding-bottom: 0;
}

.ant-form .ant-form-item .ant-form-item-label > label {
  font-size: 14px;
  margin-bottom: 4px;
  display: inline-block;
  font-weight: 500;
  color: rgb(75 85 99);
}
</style>
