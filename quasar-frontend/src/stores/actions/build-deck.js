import callApi from "src/assets/call-api";
import { useStore } from "../store";
import { Loading, Notify } from "quasar";

const runBuildDeck = (numPlayers) => {
  const store = useStore();

  const route = store.router.currentRoute.value;

  Loading.show();

  let path = `/build-deck/${numPlayers}`;
  if (store.specScheme || store.specMastermind) {
    path += `?`;
  }
  if (store.specScheme) {
    path += `scheme=${store.specScheme.id}`;
  }
  if (store.specMastermind) {
    if (store.specScheme) {
      path += `&`;
    }
    path += `mastermind=${store.specMastermind.id}`;
  }

  callApi({
    path,
    method: "get",
    useAuth: true,
  }).then((response) => {
    if (response.status != "success") {
      Notify.create({
        type: "negative",
        message: "Error building deck",
      });

      Loading.hide();

      return;
    }

    store.game = {
      ...response.game,
      created: Date.now(),
    };

    if (route.fullPath != "/") {
      store.router.push({ name: "index" });
    }

    Loading.hide();
  });
};

export default (numPlayers) => {
  const store = useStore();

  if (store.expired || !store.game) {
    runBuildDeck(numPlayers);
    return;
  }

  Notify.create({
    type: "warning",
    message: "You have an active game. Are you sure you want to override it?",
    actions: [
      {
        label: "No",
      },
      {
        label: "Yes",
        handler: () => {
          runBuildDeck(numPlayers);
        },
      },
    ],
  });
};
