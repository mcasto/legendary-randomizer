import callApi from "src/assets/call-api";
import { useStore } from "../store";

export default () => {
  const store = useStore();

  store.token = null;
  store.user = null;

  callApi({ path: "/logout", method: "post", useAuth: true }).then(() => {
    store.router.push("/login");
  });
};
