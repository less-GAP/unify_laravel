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
    { value: 0, label: "Waiting" },
    { value: 1, label: "Eligibility Check" },
    { value: 2, label: "Approve new patient" },
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
    },
    {
      key: "last_name",
      noti: "Last Name need check & update",
    },
    {
      key: "first_name",
      noti: "First Name need check & update",
    },
    {
      key: "phone",
      noti: "Phone number need check & update",
    },
    {
      key: "dob",
      noti: "Date of birth need check & update",
    },
    {
      key: "street",
      noti: "Address need check & update",
    },
    {
      key: "insurance_coverages",
      noti: "Insurance Coverages need check & update",
    },
    {
      key: "doctor_id",
      noti: "Doctor for patient need check & update",
    },
    {
      key: "doctor_status",
      noti: "Doctor status need confirm and note",
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
