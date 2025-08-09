<template>
  <div>
    <q-list dense separator>
      <q-item>
        <q-item-section>
          <q-item-label>
            <q-checkbox
              v-model="store.settings.settings.use_epics"
              @update:model-value="updateSettings"
              label="Use Epics"
            ></q-checkbox>
          </q-item-label>
        </q-item-section>
      </q-item>
      <q-item>
        <q-item-section>
          <q-item-label>
            <q-checkbox
              v-model="store.settings.settings.use_played_count"
              @update:model-value="updateSettings"
              label="Use Played Count"
            ></q-checkbox>
          </q-item-label>
        </q-item-section>
      </q-item>
    </q-list>
  </div>
</template>

<script setup>
import { clone } from "lodash-es";
import callApi from "src/assets/call-api";
import { useStore } from "src/stores/store";

const store = useStore();

const updateSettings = async () => {
  await callApi({
    path: "/settings",
    method: "put",
    payload: clone(store.settings.settings),
    useAuth: true,
  });
};
</script>
