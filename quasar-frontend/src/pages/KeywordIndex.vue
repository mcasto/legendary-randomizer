<template>
  <div class="q-pa-md">
    <q-header
      style="margin-top: 2rem;"
      class="bg-white text-black q-pa-sm shadow-1"
    >
      <q-toolbar>
        <q-input
          type="text"
          label="Search"
          stack-label
          dense
          outlined
          v-model="filter"
          clearable
          class="q-mr-sm"
          @keydown.esc="filter = null"
        >
        </q-input>

        <q-btn
          icon="chevron_left"
          :disable="pagination.currentPage == 1"
          @click="pagination.currentPage--"
        ></q-btn>

        <q-btn
          icon="chevron_right"
          :disable="pagination.currentPage == pagination.totalPages"
          @click="pagination.currentPage++"
        ></q-btn>
      </q-toolbar>
    </q-header>
    <q-list separator dense class="q-mt-xl">
      <q-item
        v-for="keyword of filteredKeywords"
        :key="`keyword-${keyword.id}`"
      >
        <q-item-section>
          {{ keyword.label }}
        </q-item-section>
        <q-item-section avatar>
          <q-icon
            name="mdi-text-box-outline"
            class="cursor-pointer"
            @click="
              dialog.keyword = keyword;
              dialog.visible = true;
            "
          />
        </q-item-section>
      </q-item>
    </q-list>

    <keyword-dialog v-model="dialog.visible" :keyword="dialog.keyword" />
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";
import { computed, ref } from "vue";
import KeywordDialog from "src/components/KeywordDialog.vue";

const store = useStore();

const filter = ref(null);

const dialog = ref({
  visible: false,
  keyword: null,
});

const pagination = ref({
  currentPage: 1,
  pageSize: 10,
  totalPages: Math.ceil(store.keywords.length / 10),
});

const filteredKeywords = computed(() => {
  const keywords = store.keywords.filter((keyword) => {
    return filter.value
      ? keyword.label.toLowerCase().includes(filter.value.toLowerCase())
      : true;
  });

  const start = (pagination.value.currentPage - 1) * pagination.value.pageSize;
  const end = start + pagination.value.pageSize;

  return keywords.slice(start, end);
});
</script>
