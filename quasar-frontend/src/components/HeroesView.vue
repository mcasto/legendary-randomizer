<template>
  <div class="full-width">
    <q-list
      separator
      :style="`background-color:${
        store.settings?.displays?.heroes?.bg || '#ffffff'
      }; color:${store.settings?.displays?.heroes?.text || '#000000'}`"
    >
      <q-item
        v-for="hero of (store.game?.deck?.heroes || []).filter((hero) => hero)"
        :key="`hero-${hero.id}`"
      >
        <q-item-section side>
          <q-icon
            name="star"
            color="orange"
            :class="!hero.special ? 'invisible' : ''"
          ></q-icon>
        </q-item-section>
        <q-item-section>
          <q-item-label>
            <div class="flex justify-between">
              <div>
                {{ hero.name }}
              </div>
              <div>
                {{ hero.set }}
              </div>
            </div>

            <div class="flex justify-between items-center">
              <div class="flex">
                <template
                  v-for="color of hero.hero_colors"
                  :key="`color-${hero.id}-${color.id}`"
                >
                  <icon-display
                    :icon="color.icon"
                    height="25"
                    width="25"
                  ></icon-display>
                </template>
              </div>
              <div class="flex">
                <template
                  v-for="team of hero.hero_teams"
                  :key="`color-${hero.id}-${team.id}`"
                >
                  <icon-display
                    :icon="team.icon"
                    height="35"
                    width="35"
                  ></icon-display>
                </template>
              </div>
            </div>
          </q-item-label>
        </q-item-section>
      </q-item>
    </q-list>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";
import IconDisplay from "./IconDisplay.vue";

const store = useStore();
</script>
