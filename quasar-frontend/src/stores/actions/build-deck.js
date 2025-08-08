import callApi from "src/assets/call-api";
import { useStore } from "../store";
import { Loading, Notify } from "quasar";
import { useRoute } from "vue-router";

export default (numPlayers) => {
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

    store.game = response.game;

    if (route.fullPath != "/") {
      store.router.push({ name: "index" });
    }

    Loading.hide();
  });
};
