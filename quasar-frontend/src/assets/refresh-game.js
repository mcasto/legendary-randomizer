import { useStore } from "src/stores/store";
import callApi from "./call-api";
import { Notify } from "quasar";
import { parseISO } from "date-fns";

export default async () => {
  const store = useStore();

  const response = await callApi({
    path: "/game",
    method: "get",
    useAuth: true,
  });

  if (response.status != "success") {
    Notify.create({ type: "negative", message: response.message });
    return;
  }

  store.game = {
    ...response.game,
    // created: parseISO(response.game.created),
  };

  setInterval(async () => {
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
  }, 1000);
};
