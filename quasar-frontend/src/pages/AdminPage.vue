<template>
  <div>
    <q-toolbar class="shadow-1">
      <q-toolbar-title>
        Admin
      </q-toolbar-title>
      <q-btn icon="home" to="/" flat></q-btn>
    </q-toolbar>
    <q-splitter :model-value="25">
      <template #before>
        <div style="height: 100vh;">
          <q-list separator>
            <q-item
              v-for="permission of permissions"
              :key="permission.id"
              clickable
              :active="permission.level == section"
              @click="section = permission.level"
            >
              <q-item-section>
                <q-item-label>
                  {{ permission.level.toUpperCase() }}
                </q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </div>
      </template>
      <template #after>
        <div>
          <admin-schemes v-if="section == 'schemes'"></admin-schemes>
          <admin-handlers v-if="section == 'handlers'"></admin-handlers>
        </div>
      </template>
    </q-splitter>
  </div>
</template>

<script setup>
import { uid } from "quasar";
import { useStore } from "src/stores/store";
import { computed, ref } from "vue";
import AdminSchemes from "./AdminSchemes.vue";
import AdminHandlers from "./AdminHandlers.vue";

const store = useStore();

const section = ref("schemes");

const permissions = computed(() => {
  return store.user.permissions.map(({ permission_level }) => {
    return {
      id: uid(),
      level: permission_level,
    };
  });
});
</script>
