import callApi from "src/assets/call-api";
import { useStore } from "../store";
import { Notify } from "quasar";

export default async () => {
  const store = useStore();

  const response = await callApi({
    path: "/handlers-required",
    method: "get",
    useAuth: true,
  });

  if (response.status == "error") {
    Notify.create({
      type: "negative",
      message: response.message,
    });
  }

  store.requiredHandlers = response.handlers;
};
