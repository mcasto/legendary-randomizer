import callApi from "src/assets/call-api";
import { useStore } from "../store";

// Loads the list of missing handlers into store.handlersRequired.
// Never throws: on any failure handlersRequired is null and the admin
// page shows the Handlers section as unavailable.
export default async () => {
  const store = useStore();
  store.handlersRequired = null;

  try {
    const response = await callApi({
      path: "/handlers-required",
      method: "get",
      useAuth: true,
    });

    if (response?.status === "success" && response.handlers) {
      store.handlersRequired = response.handlers;
    }
  } catch (e) {
    console.error("Failed to load required handlers", e);
  }
};
