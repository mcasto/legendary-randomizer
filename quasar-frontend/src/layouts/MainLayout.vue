<template>
  <q-layout view="lHh Lpr lFf">
    <q-header>
      <div class="q-px-sm text-subtitle2 flex justify-between items-center">
        <div>
          <q-btn icon="menu" flat>
            <q-menu auto-close>
              <dropdown-menu />
            </q-menu>
          </q-btn>

          Legendary Randomizer
        </div>

        <q-btn
          icon="mdi-close-circle-outline"
          flat
          round
          size="sm"
          v-if="$route.name == 'index' && store.game"
          @click="clearGame"
        ></q-btn>

        <q-btn
          icon="logout"
          flat
          size="sm"
          round
          @click="store.logout"
          v-if="canLogout"
        ></q-btn>
      </div>
    </q-header>
    <q-page-container>
      <q-page>
        <router-view />
        <q-btn
          icon="check"
          class="absolute-bottom-right q-ma-md"
          color="primary"
          size="sm"
          label="Mark Played"
          v-if="store.expired && store.game"
          @click="markPlayed"
        ></q-btn>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { useStore } from "src/stores/store";
import { computed, onBeforeUnmount, onMounted, ref, watch } from "vue";
import DropdownMenu from "src/components/DropdownMenu.vue";
import { Notify } from "quasar";
import { differenceInMinutes } from "date-fns";
import callApi from "src/assets/call-api";

const store = useStore();

const canLogout = computed(() => {
  return store.user;
});

watch(
  () => store.game,
  (newGame) => {
    if (!store.game) {
      store.expired = true;
      return;
    }

    store.expired =
      differenceInMinutes(new Date(), new Date(store.game.created)) > 15;
  }
);

const markPlayed = async () => {
  await callApi({
    path: `/mark-played`,
    method: "put",
    payload: {
      gameId: store.game.setup,
    },
    useAuth: true,
  });
};

const clearGame = () => {
  if (!store.expired) {
    Notify.create({
      type: "warning",
      message: "Are you sure you want to clear this game?",
      actions: [
        {
          label: "No",
        },
        {
          label: "Yes",
          handler: () => {
            store.game = null;
          },
        },
      ],
    });
  } else {
    store.game = null;
  }
};

onMounted(() => {
  const expiredInterval = setInterval(() => {
    const elapsed = store.game?.created
      ? differenceInMinutes(new Date(), new Date(store.game.created))
      : 0;

    store.expired = elapsed > 15;
  }, 60000);

  // Clear the interval when the component is unmounted
  onBeforeUnmount(() => {
    clearInterval(expiredInterval);
  });
});
</script>
