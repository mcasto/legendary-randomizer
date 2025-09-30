<template>
  <div class="q-pa-md">
    <q-select
      v-model="scheme"
      use-input
      input-debounce="300"
      label="Select Scheme"
      :options="options"
      option-label="name"
      dense
      outlined
      @filter="filterFn"
    >
      <template v-slot:no-option>
        <q-item>
          <q-item-section class="text-grey">
            No results
          </q-item-section>
        </q-item>
      </template>
    </q-select>

    <q-form v-if="scheme" @submit.prevent="saveOptions">
      <q-card class="q-mt-md">
        <q-toolbar>
          <q-toolbar-title>
            Edit Scheme Options
          </q-toolbar-title>
        </q-toolbar>
        <q-card-section>
          <q-input
            type="number"
            v-model.number="scheme.minPlayers"
            label="Minimum Players"
            dense
            outlined
          ></q-input>
        </q-card-section>
        <q-card-actions class="justify-end">
          <q-btn type="submit" label="Save" color="primary"></q-btn>
        </q-card-actions>
      </q-card>
    </q-form>
  </div>
</template>

<script setup>
import callApi from "src/assets/call-api";
import { useStore } from "src/stores/store";
import { ref } from "vue";

const store = useStore();

const scheme = ref(null);
const options = ref(store.admin.schemes);

function filterFn(val, update) {
  if (!!!val) {
    update(() => {
      options.value = store.admin.schemes;
    });
    return;
  }

  update(() => {
    const needle = val.toLowerCase();
    options.value = store.admin.schemes.filter((v) => {
      return v.name.toLowerCase().indexOf(needle) > -1;
    });
  });
}

const saveOptions = async () => {
  const response = await callApi({
    path: `/schemes/${scheme.value.id}`,
    method: "put",
    payload: scheme.value,
    useAuth: true,
  });
};
</script>
