import { Notify } from "quasar";
import callApi from "src/assets/call-api";
import { useStore } from "../store";

export default ({ email, password }) => {
  const store = useStore();

  callApi({
    path: "/login",
    method: "post",
    payload: { email, password },
  }).then((response) => {
    if (response?.error || !response?.user) {
      Notify.create({
        type: "negative",
        message: response?.error
          ? `${response.error.status ?? ""}: ${response.error.json?.message ?? "Server error"}`
          : response?.message ?? "Login failed",
      });
      return;
    }

    store.user = response.user;
    store.token = response.token;
    store.view = store.user.default_view;

    if (response.status == "success") {
      store.router.push({ name: "index" });
    }
  });
};
