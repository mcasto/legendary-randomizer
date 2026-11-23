import { useStore } from "src/stores/store";
import callApi from "./call-api";
import { Notify } from "quasar";
import { parseISO } from "date-fns";
import wretch from "wretch";

let pollTimer = null;

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

  // Only ever run one poller. This function runs every time the "/" route is
  // entered (e.g. returning from /admin); without this each visit stacked
  // another 1-second poller, multiplying requests and DB connections.
  if (pollTimer) clearInterval(pollTimer);

  pollTimer = setInterval(async () => {
    const response = await callApi({
      path: "/game",
      method: "get",
      useAuth: true,
    });

    if (response.status != "success") {
      if (!response.message) {
        return;
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
