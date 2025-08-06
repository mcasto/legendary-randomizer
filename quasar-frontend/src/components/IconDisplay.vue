<template>
  <div v-html="sizedIcon"></div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps(["icon", "width", "height"]);

const sizedIcon = computed(() => {
  // Replace width/height in the SVG string
  return props.icon
    .replace(/width="[^"]*"/, `width="${props.width}"`) // New width
    .replace(/height="[^"]*"/, `height="${props.height}"`); // New height
});
</script>
