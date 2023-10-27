import Api from "./Api";

const fetchListInsurancesApi = async function () {
  const list = await Api.get("master-data/insurance").then((res) => res.data);
  return JSON.parse(list.data);
};
const fetchListDoctorsApi = async function () {
  var list = await Api.get('doctor/list').then((res) => res.data);
  var data = [];
  list.data.forEach((item) => {
    data.push(
      {
        value: item.id,
        label: item.full_name,
      }
    )
  })
  return data;
};
const fetchListStatusPatientApi = async function () {
  return await Api.get("master-data/task-status");
};
const getStatusPatient = async function (value) {
  var list = await fetchListStatusPatientApi().then((res) => res.data);
  list = JSON.parse(list.data)
  return list.find((item) => item.value === value);
};

const fetchListDoctorStatusApi = function () {
  return [
    {
      value: 0,
      label: "Unknow",
    },
    {
      value: 1,
      label: "Waiting",
    },
    {
      value: 2,
      label: "Confirm",
    },
    {
      value: 3,
      label: "Done",
    },
  ];
};

const listProcess = function () {
  return [
    {value: 0, label: "Waiting"},
    {value: 1, label: "Eligibility Check"},
    {value: 2, label: "Approve new patient"},
  ];
};

const getProcess = function (value) {
  const list = listProcess();
  return list.find((item) => item.value === value);
};

const useNeedToDoList = function () {
  return [
    {
      key: "full_name",
      noti: "Full Name need check & update",
      check: null
    },
    {
      key: "last_name",
      noti: "Last Name need check & update",
      check: null
    },
    {
      key: "first_name",
      noti: "First Name need check & update",
      check: null
    },
    {
      key: "phone",
      noti: "Phone number need check & update",
      check: null
    },
    {
      key: "dob",
      noti: "Date of birth need check & update",
      check: null
    },
    {
      key: "street",
      noti: "Address need check & update",
      check: null
    },
    {
      key: "insurances",
      noti: "Insurance Coverages need check & update",
      check: 0
    },
    {
      key: "doctor_id",
      noti: "Doctor for patient need check & update",
      check: null
    },
    {
      key: "doctor_status",
      noti: "Doctor status need confirm",
      check: 0
    },
    {
      key: "doctor_comment",
      noti: "Doctor status need note",
      check: null
    },
    {
      key: "products",
      noti: "Products need need check & update",
      check: 0
    },
  ];
};

export {
  fetchListInsurancesApi,
  fetchListDoctorsApi,
  fetchListStatusPatientApi,
  getStatusPatient,
  fetchListDoctorStatusApi,
  listProcess,
  getProcess,
  useNeedToDoList,
};
