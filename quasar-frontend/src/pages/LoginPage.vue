<template>
  <div class="flex justify-center q-pt-xl">
    <div style="width: 260px;" class="flex column align-middle">
      <q-img src="/legendary-logo.png"></q-img>
      <div class="q-mt-xl">
        <q-form @submit="store.login({ email, password })">
          <div class="column q-gutter-y-sm">
            <q-input
              type="email"
              label="Email"
              v-model="email"
              dense
              outlined
            ></q-input>
            <q-input
              :type="showPass ? 'text' : 'password'"
              label="Password"
              v-model="password"
              dense
              outlined
            >
              <template #append>
                <q-btn
                  :icon="showPass ? 'visibility_off' : 'visibility'"
                  round
                  flat
                  @click="showPass = !showPass"
                ></q-btn>
              </template>
            </q-input>
            <div class="flex justify-end">
              <q-btn type="submit" label="Login" color="primary"></q-btn>
            </div>
          </div>
        </q-form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";
import { ref } from "vue";

const store = useStore();

const showPass = ref(false);

const email = ref(null);
const password = ref(null);
</script>
