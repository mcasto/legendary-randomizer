<template>
  <div class="q-pa-md">
    <div class="col">
      <q-select
        :options="store.entities.schemes"
        v-model="store.specScheme"
        emit-value
        map-options
        option-label="name"
        filled
        dense
        class="q-mb-md"
        label="Scheme"
        clearable
      />
    </div>
    <div class="col">
      <q-select
        :options="store.entities.masterminds"
        v-model="store.specMastermind"
        emit-value
        map-options
        option-label="name"
        filled
        dense
        class="q-mb-md"
        label="Mastermind"
        clearable
      />
    </div>

    <div class="flex justify-end">
      <q-btn class="q-mb-md" color="primary" label="Start Game">
        <q-menu auto-close>
          <q-list separator>
            <q-item dense class="bg-primary text-white">
              <q-item-section>
                <q-item-label>
                  New Game Setup
                </q-item-label>
              </q-item-section>
            </q-item>
            <q-item
              v-for="numPlayers of 5"
              :key="`num-players-${numPlayers}`"
              clickable
              :active="numPlayers == store.game?.setup.players"
              @click="store.buildDeck(numPlayers)"
            >
              <q-item-section>
                <q-item-label>
                  {{ numPlayers }} Player{{ numPlayers > 1 ? "s" : "" }}
                </q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-menu>
      </q-btn>
    </div>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";
const store = useStore();
</script>
