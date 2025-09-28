<template>
  <q-toolbar v-if="store.view" class="shadow-1">
    <q-btn icon="fa-solid fa-shuffle" size="sm" flat round>
      <q-menu auto-close>
        <q-list separator>
          <q-item dense class="bg-primary text-white">
            <q-item-section>
              <q-item-label>
                New Game Setup
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item
            v-for="numPlayers of 5"
            :key="`num-players-${numPlayers}`"
            clickable
            :active="numPlayers == store.game?.setup.players"
            @click="store.buildDeck(numPlayers)"
          >
            <q-item-section>
              <q-item-label>
                {{ numPlayers }} Player{{ numPlayers > 1 ? "s" : "" }}
              </q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </q-menu>
    </q-btn>
    <q-toolbar-title>
      <div class="text-subtitle2">
        {{ store.view.toUpperCase() }}
      </div>
      <div class="text-caption" v-if="store.game?.setup.players">
        {{ store.game?.setup.players }} Players
      </div>
    </q-toolbar-title>
    <q-btn
      :icon="`fa-solid ${store.view == 'enemies' ? 'fa-shield' : 'fa-skull'}`"
      flat
      round
      size="sm"
      class="q-mr-xl"
      @click="store.view = store.view == 'heroes' ? 'enemies' : 'heroes'"
    ></q-btn>
  </q-toolbar>
  <component :is="ViewComponent"></component>
  <div class="q-pa-lg"></div>
</template>

<script setup>
import { useStore } from "src/stores/store";
import HeroesView from "src/components/HeroesView.vue";
import EnemiesView from "src/components/EnemiesView.vue";
import { computed } from "vue";

const store = useStore();

console.log({ user: store.user });

const ViewComponent = computed(() => {
  return store.view == "heroes" ? HeroesView : EnemiesView;
});
</script>
