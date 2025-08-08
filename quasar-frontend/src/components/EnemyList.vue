<template>
  <div
    class="q-px-sm q-pt-sm"
    :style="`background-color:${bg}; color:${text};`"
  >
    <div class="text-subtitle2">
      {{ label }}
    </div>
    <div>
      <q-list dense>
        <q-item v-for="enemy of enemies" :key="`${label}-${enemy.id}`">
          <q-item-section side>
            <q-icon
              name="star"
              color="orange"
              :class="!enemy.special ? 'invisible' : ''"
            ></q-icon>
          </q-item-section>
          <q-item-section>
            <q-item-label>
              {{ enemy.name }}
              <div v-if="enemy.always_leads" class="text-caption">
                Always Leads: {{ enemy.always_leads }}
              </div>
            </q-item-label>
          </q-item-section>
          <q-item-section side v-if="showSpecified">
            <q-icon name="star" color="primary"></q-icon>
          </q-item-section>
        </q-item>
      </q-list>
    </div>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";
import { computed } from "vue";

const props = defineProps(["label", "enemies", "bg", "text"]);

const store = useStore();

const showSpecified = computed(() => {
  switch (props.label) {
    case "Scheme(s)":
      return store.specScheme;
    case "Mastermind(s)":
      return store.specMastermind;
    default:
      return false;
  }
});
</script>
