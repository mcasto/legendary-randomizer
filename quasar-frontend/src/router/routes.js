import { before } from "lodash-es";
import { Notify } from "quasar";
import callApi from "src/assets/call-api";
import { useStore } from "src/stores/store";

const routes = [
  {
    path: "/",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        component: () => import("pages/IndexPage.vue"),
        meta: { requireAuth: true },
        name: "index",
        beforeEnter: async () => {
          const store = useStore();
          store.settings = await callApi({
            path: "/user-settings",
            method: "get",
            useAuth: true,
          });
        },
      },
      {
        path: "login",
        component: () => import("pages/LoginPage.vue"),
      },
      { path: "register", component: () => import("pages/RegisterPage.vue") },
      {
        path: "game-setup",
        component: () => import("pages/GameSetupPage.vue"),
        meta: { requireAuth: true },
        name: "game-setup",
        beforeEnter: async () => {
          const store = useStore();
          const response = await callApi({
            path: "/entities",
            method: "get",
            useAuth: true,
          });

          if (response.status != "success") {
            Notify.create({
              type: "negative",
              message: `Error ${response.error.status}: ${response.error.json.message}`,
            });
          }

          store.entities = response.data;
        },
      },
      {
        path: "keyword-index",
        component: () => import("pages/KeywordIndex.vue"),
        meta: { requireAuth: true },
        name: "keyword-index",
        beforeEnter: async () => {
          const store = useStore();
          const response = await callApi({
            path: "/keywords",
            method: "get",
            useAuth: true,
          });

          if (response.status != "success") {
            Notify.create({
              type: "negative",
              message: `Error ${response.error.status}: ${response.error.json.message}`,
            });
          }

          store.keywords = response.data;
        },
      },
      {
        path: "user-settings",
        component: () => import("pages/UserSettings.vue"),
        meta: { requireAuth: true },
        name: "user-settings",
        beforeEnter: async () => {
          const store = useStore();
          const sets = await callApi({
            path: "/sets",
            method: "get",
            useAuth: true,
          });

          if (sets.status != "success") {
            Notify.create({
              type: "negative",
              message: `Error ${sets.error.status}: ${sets.error.json.message}`,
            });
          }

          store.sets = sets.data;

          const response = await callApi({
            path: "/user-settings",
            method: "get",
            useAuth: true,
          });

          if (response.status != "success") {
            Notify.create({
              type: "negative",
              message: `Error ${response.error.status}: ${response.error.json.message}`,
            });
          }

          store.settings = response;
        },
      },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/ErrorNotFound.vue"),
  },
];

export default routes;
