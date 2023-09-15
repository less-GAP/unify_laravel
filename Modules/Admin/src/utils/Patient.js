import Api from "./Api";
const fetchListStatesApi = function () {
  // return Api.get('state' + '/list', {params});
  return [
    { code: "AL", name: "Alabama" },
    { code: "AK", name: "Alaska" },
    { code: "AZ", name: "Arizona" },
    { code: "AR", name: "Arkansas" },
    { code: "CA", name: "California" },
    { code: "CO", name: "Colorado" },
    { code: "CT", name: "Connecticut" },
    { code: "DE", name: "Delaware" },
    { code: "DC", name: "District Of Columbia" },
    { code: "FL", name: "Florida" },
    { code: "GA", name: "Georgia" },
    { code: "HI", name: "Hawaii" },
    { code: "ID", name: "Idaho" },
    { code: "IL", name: "Illinois" },
    { code: "IN", name: "Indiana" },
    { code: "IA", name: "Iowa" },
    { code: "KS", name: "Kansas" },
    { code: "KY", name: "Kentucky" },
    { code: "LA", name: "Louisiana" },
    { code: "ME", name: "Maine" },
    { code: "MD", name: "Maryland" },
    { code: "MA", name: "Massachusetts" },
    { code: "MI", name: "Michigan" },
    { code: "MN", name: "Minnesota" },
    { code: "MS", name: "Mississippi" },
    { code: "MO", name: "Missouri" },
    { code: "MT", name: "Montana" },
    { code: "NE", name: "Nebraska" },
    { code: "NV", name: "Nevada" },
    { code: "NH", name: "New Hampshire" },
    { code: "NJ", name: "New Jersey" },
    { code: "NM", name: "New Mexico" },
    { code: "NY", name: "New York" },
    { code: "NC", name: "North Carolina" },
    { code: "ND", name: "North Dakota" },
    { code: "OH", name: "Ohio" },
    { code: "OK", name: "Oklahoma" },
    { code: "OR", name: "Oregon" },
    { code: "PA", name: "Pennsylvania" },
    { code: "RI", name: "Rhode Island" },
    { code: "SC", name: "South Carolina" },
    { code: "SD", name: "South Dakota" },
    { code: "TN", name: "Tennessee" },
    { code: "TX", name: "Texas" },
    { code: "UT", name: "Utah" },
    { code: "VT", name: "Vermont" },
    { code: "VA", name: "Virginia" },
    { code: "WA", name: "Washington" },
    { code: "WV", name: "West Virginia" },
    { code: "WI", name: "Wisconsin" },
    { code: "WY", name: "Wyoming" },
  ];
};
const fetchListInsurancesApi = async function () {
  const list = await Api.get("master-data/insurance").then((res) => res.data);
  return JSON.parse(list.data);
};
const fetchListDoctorsApi = function () {
  // return Api.get('doctor' + '/list', { params });
  return [
    {
      value: 0,
      label: "Dr. John Doe",
    },
    {
      value: 1,
      label: "Dr. Mike Donald",
    },
  ];
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
  // return Api.get('doctor' + '/list_status', { params });
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
  fetchListStatesApi,
  fetchListInsurancesApi,
  fetchListDoctorsApi,
  fetchListStatusPatientApi,
  getStatusPatient,
  fetchListDoctorStatusApi,
  listProcess,
  getProcess,
  useNeedToDoList,
};
