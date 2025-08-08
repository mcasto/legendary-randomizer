import { defineStore } from "pinia";
import { ref, computed } from "vue";
import logout from "./actions/logout";
import login from "./actions/login";
import verifyToken from "./actions/verify-token";
import buildDeck from "./actions/build-deck";

export const useStore = defineStore(
  "store",
  () => {
    const state = {
      entities: ref(null),
      game: ref(null),
      keywords: ref(null),
      settings: ref(null),
      specMastermind: ref(null),
      specScheme: ref(null),
      token: ref(null),
      user: ref(null),
      view: ref(null),
    };
    const getters = {};
    const actions = {
      buildDeck,
      login,
      logout,
      verifyToken,
    };

    return { ...state, ...getters, ...actions };
  },
  {
    persist: {
      key: "legendary-randomizer-2025",
    },
  }
);
