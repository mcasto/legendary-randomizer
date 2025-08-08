<template>
  <div>
    <q-tabs v-model="tab">
      <q-tab name="sets">
        Sets
      </q-tab>
      <q-tab name="use">
        Use
      </q-tab>
      <q-tab name="display">
        Display
      </q-tab>
    </q-tabs>

    <q-tab-panels v-model="tab">
      <q-tab-panel name="sets">
        <sets-tab />
      </q-tab-panel>
      <q-tab-panel name="use">
        <use-tab />
      </q-tab-panel>
      <q-tab-panel name="display">
        <display-tab />
      </q-tab-panel>
    </q-tab-panels>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";
import { ref } from "vue";
import SetsTab from "src/components/SetsTab.vue";
import UseTab from "src/components/UseTab.vue";
import DisplayTab from "src/components/DisplayTab.vue";

const store = useStore();

const tab = ref("sets");
</script>
