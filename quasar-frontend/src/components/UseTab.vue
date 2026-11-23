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
      <q-item>
        <q-item-section>
          <q-item-label>
            <q-btn
              outline
              color="negative"
              icon="restart_alt"
              label="Reset Played Count"
              :loading="resetting"
              @click="confirmReset = true"
            />
          </q-item-label>
        </q-item-section>
      </q-item>
    </q-list>

    <q-dialog v-model="confirmReset">
      <q-card>
        <q-card-section class="text-h6">Reset played count?</q-card-section>
        <q-card-section class="q-pt-none">
          This clears the played count for every scheme, mastermind, villain,
          henchman and hero. It can't be undone.
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="Cancel" v-close-popup />
          <q-btn
            flat
            color="negative"
            label="Reset"
            v-close-popup
            @click="resetPlayedCount"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { Notify } from "quasar";
import { clone } from "lodash-es";
import callApi from "src/assets/call-api";
import { useStore } from "src/stores/store";

const store = useStore();

const confirmReset = ref(false);
const resetting = ref(false);

const resetPlayedCount = async () => {
  resetting.value = true;
  try {
    const response = await callApi({
      path: "/played-counts",
      method: "delete",
      useAuth: true,
    });
    if (response?.status === "success") {
      Notify.create({ type: "positive", message: "Played count reset" });
    }
  } finally {
    resetting.value = false;
  }
};

const updateSettings = async () => {
  await callApi({
    path: "/settings",
    method: "put",
    payload: clone(store.settings.settings),
    useAuth: true,
  });
};
</script>
