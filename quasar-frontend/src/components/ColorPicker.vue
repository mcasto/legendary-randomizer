<template>
  <div v-if="visible">
    <q-card>
      <q-toolbar>
        <div>
          <q-toolbar-title> Colors for {{ model.label }} </q-toolbar-title>
          <div>
            <q-tabs v-model="tab">
              <q-tab name="background">
                Background
              </q-tab>
              <q-tab name="text">
                Text
              </q-tab>
            </q-tabs>
            <q-tab-panels v-model="tab">
              <q-tab-panel name="background">
                <q-color v-model="model.bg" default-view="palette" />
              </q-tab-panel>
              <q-tab-panel name="text">
                <q-color v-model="model.text" default-view="palette" />
              </q-tab-panel>
            </q-tab-panels>
          </div>
        </div>
      </q-toolbar>

      <q-card-actions class="justify-between">
        <q-btn label="Cancel" @click="$emit('close')" />
        <q-btn label="Update" @click="$emit('update')"></q-btn>
      </q-card-actions>
    </q-card>
  </div>
</template>

<script setup>
import { ref } from "vue";

const model = defineModel();
const props = defineProps(["visible"]);
const emits = defineEmits(["close", "update"]);

const tab = ref("background");
</script>
