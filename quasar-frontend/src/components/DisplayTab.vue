<template>
  <div>
    <q-list separator ref="listRef">
      <q-item
        v-for="(section, index) in sectionsData"
        :key="section.key"
        :style="`background-color:${section.bg || '#ffffff'}; color:${
          section.text || '#000000'
        }`"
      >
        <q-item-section avatar>
          <q-icon name="drag_indicator" class="drag-handle" />
        </q-item-section>
        <q-item-section>
          <q-item-label>{{ section.label }}</q-item-label>
        </q-item-section>
        <q-item-section avatar>
          <q-btn
            icon="mdi-palette"
            flat
            round
            dense
            @click="openColorPicker(section)"
          >
          </q-btn>
        </q-item-section>
      </q-item>
    </q-list>

    <color-picker
      v-model="colorModel"
      :visible="colorVisible"
      @update="updateDisplays"
      @close="colorVisible = false"
    ></color-picker>
  </div>
</template>

<script setup>
import { startCase } from "lodash-es";
import { useStore } from "src/stores/store";
import { ref } from "vue";
import { useSortable } from "@vueuse/integrations/useSortable";
import { clone } from "lodash-es";
import callApi from "src/assets/call-api";
import ColorPicker from "./ColorPicker.vue";

const store = useStore();

const listRef = ref();
const sectionsData = ref([]);
const colorModel = ref("null");
const colorVisible = ref(false);

// Initialize sectionsData from store once
const initializeSections = () => {
  if (!store.settings?.displays) return;

  sectionsData.value = Object.entries(store.settings.displays)
    .map(([key, value]) => ({
      label: startCase(key),
      key,
      ...value,
    }))
    .sort((a, b) => (a.order || 0) - (b.order || 0))
    .filter((section) => section.key !== "heroes");
};

// Initialize once when component mounts
initializeSections();

// Set up sortable functionality
useSortable(listRef, sectionsData, {
  handle: ".drag-handle",
  animation: 150,
  ghostClass: "sortable-ghost",
  chosenClass: "sortable-chosen",
  onEnd: (event) => {
    // Manually update the array based on the move
    if (
      event.oldIndex !== undefined &&
      event.newIndex !== undefined &&
      event.oldIndex !== event.newIndex
    ) {
      const movedSection = sectionsData.value[event.oldIndex];

      // Remove the item from old position and insert at new position
      sectionsData.value.splice(event.oldIndex, 1);
      sectionsData.value.splice(event.newIndex, 0, movedSection);
    }

    updateDisplays();
  },
});

const updateDisplays = async () => {
  if (!store.settings?.displays) return;

  // Update store based on the corrected sectionsData array
  sectionsData.value.forEach((section, index) => {
    if (store.settings.displays[section.key]) {
      const newOrder = index + 1;
      store.settings.displays[section.key].order = newOrder;
      store.settings.displays[section.key].bg = section.bg;
      store.settings.displays[section.key].text = section.text;
    }
  });

  const settings = clone(store.settings);

  await callApi({
    path: "/settings",
    method: "put",
    payload: settings,
    useAuth: true,
  });
};

const openColorPicker = (section) => {
  colorVisible.value = true;
  colorModel.value = section;
};
</script>

<style scoped>
.drag-handle {
  cursor: move;
  color: #9e9e9e;
}

.drag-handle:hover {
  color: #616161;
}

.sortable-ghost {
  opacity: 0.5;
}

.sortable-chosen {
  opacity: 0.8;
}
</style>
