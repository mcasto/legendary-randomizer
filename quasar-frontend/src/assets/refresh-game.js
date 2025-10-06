import { useStore } from "src/stores/store";
import callApi from "./call-api";
import { Notify } from "quasar";

export default async () => {
  const store = useStore();

  if (!store.game) {
    const response = await callApi({
      path: "/game",
      method: "get",
      useAuth: true,
    });

    if (response.status != "success") {
      Notify.create({ type: "negative", message: response.message });
      return;
    }

    store.game = response.game;
  }

  setInterval(async () => {
    if (!store.game) {
      const response = await callApi({
        path: "/game",
        method: "get",
        useAuth: true,
      });

      if (response.status != "success") {
        Notify.create({ type: "negative", message: response.message });
        return;
      }

      store.game = response.game;
    }
  }, 1000);
};
