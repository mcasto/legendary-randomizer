<template>
  <q-dialog v-model="visible" persistent>
    <q-card>
      <div class="flex justify-between q-ml-sm items-center">
        <div class="text-h6">
          {{ keyword.label }}
        </div>

        <q-btn
          icon="close"
          flat
          round
          size="sm"
          @click="visible = false"
        ></q-btn>
      </div>
      <q-separator></q-separator>
      <q-card-section v-html="keyword.details"> </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script setup>
const visible = defineModel();
const props = defineProps(["keyword"]);
</script>
