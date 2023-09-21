import { notification } from "ant-design-vue";
import Api from "@/utils/Api";
import { useAuthStore } from "@/stores/auth";
const auth = useAuthStore();
import dayjs from "dayjs";

const updateTaskApi = function (id, data) {
  return Api.put("task/" + id, data);
};

const statusTasks = function () {
  return [
    { value: 0, label: "Waiting", color: "orange" },
    { value: 1, label: "Working", color: "blue" },
    { value: 2, label: "Completed / Need Review", color: "yellow" },
    { value: 3, label: "Done", color: "green" },
  ];
};

const getStatusTask = function (value) {
  const list = statusTasks();
  return value===null ? list[0] : list.find((item) => item.value === value);
}

// const logTaskApi = function (id, data) {
//   return Api.post("task/" + id + "/log", data);
// }

const deleteTask = function (id, actionAfterDelete = null) {
  updateTaskApi(id, {
    deleted: 1,
    deleted_by: auth.user.id,
    deleted_at: dayjs().format("YYYY-MM-DD HH:mm:ss"),
  }).then((rs) => {
    if (actionAfterDelete) {
      actionAfterDelete();
    }
    notification.success({
      message: "Review this task in Archives!",
    });
  });
};

const completeTask = function (id, actionAfterComplete = null) {
  updateTaskApi(id, {
    is_completed: 1,
    task_process: 2, // need review
    completed_at: dayjs().format("YYYY-MM-DD HH:mm:ss"),
  }).then((rs) => {
    if (actionAfterComplete) {
      actionAfterComplete();
    }
    notification.success({
      message: "You have completed this task!",
    });
  });
};
const reviewTask = function (id, status, actionReviewComplete = null) {
  updateTaskApi(id, {
    is_completed: status ? 1 : 0,
    task_process: status ? 3 : 0, // true => done, false => waiting for rework
  }).then((rs) => {
    if (actionReviewComplete) {
      actionReviewComplete();
    }
    notification.success({
      message: status ? "You reviewed & accepted this task!" : "You reviewed & rejected this task! Assignees will rework!",
    });
  });
};

const workingTask = function (id, actionAfterWorking = null, pending = false) {
  updateTaskApi(id, {
    is_completed: 0,
    task_process: pending ? 0 : 1, // need review
    completed_at: null,
  }).then((rs) => {
    if (actionAfterWorking) {
      actionAfterWorking();
    }
    notification.success({
      message: "You change this task to Working status!",
    });
  });
};

export { deleteTask, completeTask, workingTask, reviewTask, getStatusTask, statusTasks };
