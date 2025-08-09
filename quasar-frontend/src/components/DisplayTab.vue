<template>
  <div>
    <q-toolbar>
      <q-toolbar-title>
        Layout
      </q-toolbar-title>
    </q-toolbar>

    <q-list separator ref="listRef">
      <q-item
        v-for="(section, index) in sectionsData"
        :key="section.key"
        clickable
        v-ripple
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
        <q-item-section side>
          <!--  -->
        </q-item-section>
      </q-item>
    </q-list>
  </div>
</template>

<script setup>
import { startCase } from "lodash-es";
import { useStore } from "src/stores/store";
import { ref } from "vue";
import { useSortable } from "@vueuse/integrations/useSortable";
import { clone } from "lodash-es";
import callApi from "src/assets/call-api";

const store = useStore();

const listRef = ref();
const sectionsData = ref([]);

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
