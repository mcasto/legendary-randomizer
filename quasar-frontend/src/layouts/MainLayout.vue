<template>
  <q-layout view="lHh Lpr lFf">
    <q-header>
      <div class="q-px-sm text-subtitle2 flex justify-between items-center">
        <div>
          <q-btn icon="menu" flat>
            <q-menu auto-close>
              <q-list separator>
                <q-item clickable :to="{ name: 'index' }" exact>
                  <q-item-section avatar>
                    <q-icon name="home" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>
                      Home
                    </q-item-label>
                  </q-item-section>
                </q-item>

                <q-item clickable :to="{ name: 'game-setup' }">
                  <q-item-section avatar>
                    <q-img src="/game-settings.png" style="width: 24px;" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>
                      Specify Game Setup
                    </q-item-label>
                  </q-item-section>
                </q-item>

                <q-item clickable :to="{ name: 'keyword-index' }">
                  <q-item-section avatar>
                    <q-icon name="key" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>
                      Keyword Index
                    </q-item-label>
                  </q-item-section>
                </q-item>

                <q-item clickable :to="{ name: 'user-settings' }">
                  <q-item-section avatar>
                    <q-icon name="settings" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>
                      User Settings
                    </q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-menu>
          </q-btn>

          Legendary Randomizer
        </div>
        <q-btn
          icon="logout"
          flat
          size="sm"
          round
          @click="store.logout"
          v-if="canLogout"
        ></q-btn>
      </div>
    </q-header>
    <q-page-container>
      <q-page>
        <router-view />
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { useStore } from "src/stores/store";
import { computed } from "vue";

const store = useStore();

const canLogout = computed(() => {
  return store.user;
});
</script>
