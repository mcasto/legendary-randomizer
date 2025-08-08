<template>
  <div>
    <q-input
      type="text"
      v-model="filter"
      label="Search"
      stack-label
      dense
      outlined
      clearable
    ></q-input>
    <q-list dense separator>
      <q-item v-for="set of filteredSets">
        <set-item :set="set"></set-item>
      </q-item>
    </q-list>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";
import SetItem from "src/components/SetItem.vue";
import { computed, ref } from "vue";

const filter = ref(null);

const store = useStore();

const filteredSets = computed(() => {
  if (!filter.value) return store.sets;

  return store.sets.filter((set) =>
    set.label.toLowerCase().includes(filter.value.toLowerCase())
  );
});
</script>
