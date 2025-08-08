<template>
  <q-item-section avatar>
    <q-checkbox v-model="hasSet" size="xs"></q-checkbox>
  </q-item-section>
  <q-item-section>
    <q-item-label>
      {{ set.label }}
    </q-item-label>
  </q-item-section>
</template>

<script setup>
import { remove } from "lodash-es";
import callApi from "src/assets/call-api";
import { useStore } from "src/stores/store";
import { computed } from "vue";

const props = defineProps(["set"]);

const store = useStore();

const hasSet = computed({
  get: () => {
    return store.settings.sets.map(({ id }) => id).includes(props.set.id);
  },
  set: (v) => {
    if (v) {
      addSet();
    } else {
      removeSet();
    }
  },
});

const addSet = async () => {
  store.settings.sets.push({
    id: props.set.id,
    label: props.set.label,
  });

  await callApi({
    path: `/sets/${props.set.value}/add`,
    method: "put",
    useAuth: true,
  });
};

const removeSet = async () => {
  remove(store.settings.sets, ({ id }) => id === props.set.id);

  await callApi({
    path: `/sets/${props.set.value}/remove`,
    method: "put",
    useAuth: true,
  });
};
</script>
