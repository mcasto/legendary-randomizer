import callApi from "src/assets/call-api";
import { useStore } from "../store";

export default () => {
  const store = useStore();

  return callApi({ path: "/verify-token", method: "post", useAuth: true }).then(
    ({ status }) => {
      return status === "success";
    }
  );
};
