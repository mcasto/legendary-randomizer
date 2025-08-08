<template>
  <div>
    <enemy-list
      v-for="display in displays"
      :key="display.key"
      :label="display.label"
      :enemies="store.game.deck[display.key]"
      :bg="display.bg"
      :text="display.text"
      class="q-mb-xs"
    ></enemy-list>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";
import EnemyList from "./EnemyList.vue";
import { startCase } from "lodash-es";

const store = useStore();
const displays = Object.entries(store.settings.displays)
  .filter(([key, value]) => key != "heroes")
  .map(([key, value]) => {
    const label = startCase(key);
    return { key, label, ...value };
  })
  .sort((a, b) => a.order - b.order);

console.log({ displays });
</script>
