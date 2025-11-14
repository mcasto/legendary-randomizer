import { useStore } from "src/stores/store";
import callApi from "./call-api";
import { Notify } from "quasar";
import { parseISO } from "date-fns";
import wretch from "wretch";

export default async () => {
  const store = useStore();

  const response = await callApi({
    path: "/game",
    method: "get",
    useAuth: true,
  });

  if (response.status != "success") {
    Notify.create({
      type: "negative",
      message: response.message || "refresh-game-js-01",
    });
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
      if (!response.message) {
        await callApi({
          path: "/log-refresh-error",
          method: "post",
          payload: response,
        });
      }

      Notify.create({
        type: "negative",
        message: response.message || "refresh-game-js-02",
      });
      return;
    }

    store.game = response.game;
  }, 1000);
};
