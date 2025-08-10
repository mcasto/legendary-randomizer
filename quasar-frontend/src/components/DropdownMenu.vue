<template>
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
</template>
