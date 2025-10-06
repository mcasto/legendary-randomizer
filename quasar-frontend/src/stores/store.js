import { defineStore } from "pinia";
import { ref, computed } from "vue";
import logout from "./actions/logout";
import login from "./actions/login";
import verifyToken from "./actions/verify-token";
import buildDeck from "./actions/build-deck";
import requiredHandlers from "./actions/required-handlers";

export const useStore = defineStore(
  "store",
  () => {
    const state = {
      admin: ref({}),
      entities: ref(null),
      game: ref(null),
      expired: ref(null),
      handlersRequired: ref(null),
      keywords: ref(null),
      sets: ref(null),
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
      requiredHandlers,
      verifyToken,
    };

    return { ...state, ...getters, ...actions };
  },
  {
    persist: {
      key: "legendary-randomizer-2025",
      omit: ["game"],
    },
  }
);
