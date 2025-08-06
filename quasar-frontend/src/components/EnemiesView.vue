<template>
  <div>
    <q-card bordered flat>
      <enemy-list
        label="Scheme(s)"
        :enemies="store.game.deck.schemes"
        color="blue-3"
      ></enemy-list>
      <enemy-list
        label="Mastermind(s)"
        :enemies="store.game.deck.masterminds"
        color="red-5"
      ></enemy-list>
      <enemy-list
        label="Villains"
        :enemies="store.game.deck.villains"
        color="red-4"
      ></enemy-list>
      <enemy-list
        label="Henchmen"
        :enemies="store.game.deck.henchmen"
        color="red-3"
      ></enemy-list>
    </q-card>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";
import EnemyList from "./EnemyList.vue";

const store = useStore();
</script>
