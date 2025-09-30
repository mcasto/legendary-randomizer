<template>
  <div class="q-pa-md">
    <div class="text-h6">
      Required Handlers
    </div>

    <div v-if="!store.requiredHandlers" class="text-center q-pa-xl">
      There was an error retrieving the handlers.
    </div>

    <div v-else>
      <q-tabs v-model="tab">
        <q-tab name="schemes">
          Schemes
        </q-tab>
        <q-tab name="masterminds">
          Masterminds
        </q-tab>
      </q-tabs>

      <q-tab-panels v-model="tab">
        <q-tab-panel name="schemes">
          <div v-if="store.requiredHandlers.schemes.length == 0">
            No Required Handlers Found
          </div>

          <q-list dense separator v-else>
            <q-item
              v-for="handler of store.requiredHandlers.schemes"
              :key="handler.id"
            >
              <q-item-section>
                <q-item-label>
                  {{ handler.name }}
                </q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-tab-panel>
        <q-tab-panel name="masterminds">
          <div v-if="store.requiredHandlers.masterminds.length == 0">
            No Required Handlers Found
          </div>

          <q-list dense separator v-else>
            <q-item
              v-for="handler of store.requiredHandlers.masterminds"
              :key="handler.id"
            >
              <q-item-section>
                <q-item-label>
                  {{ handler.name }}
                </q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-tab-panel>
      </q-tab-panels>
    </div>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";
import { ref } from "vue";

const store = useStore();

const tab = ref("schemes");
</script>
