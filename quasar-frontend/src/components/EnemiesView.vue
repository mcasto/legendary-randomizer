<template>
  <div>
    <div
      v-if="isEmpty(store.game?.deck)"
      class="flex flex-center text-h6"
      style="height: 100vh;"
    >
      No Active Game
    </div>

    <enemy-list
      v-for="display in displays"
      :key="display.key"
      :label="display.label"
      :enemies="store.game?.deck?.[display.key] || []"
      :bg="display.bg"
      :text="display.text"
      class="q-mb-xs"
      v-else
    ></enemy-list>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";
import EnemyList from "./EnemyList.vue";
import { isEmpty, startCase } from "lodash-es";

const store = useStore();
const displays = Object.entries(store.settings?.displays || {})
  .filter(([key, value]) => key != "heroes")
  .map(([key, value]) => {
    const label = startCase(key);
    return { key, label, ...value };
  })
  .sort((a, b) => (a.order || 0) - (b.order || 0));
</script>
