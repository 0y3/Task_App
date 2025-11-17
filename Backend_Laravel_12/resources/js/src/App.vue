<script lang="ts" setup>
    import { ref } from 'vue';

    const message = ref<string>('Hello, Vue 3 with TypeScript!');
    const baseUrl = import.meta.env.VITE_APP_URL || import.meta.env.BASE_URL || '/';
</script>
<template>
  <div class="container">
    <!-- <h1>{{ message }}</h1> -->
    <!-- <p>Base URL: {{ baseUrl }}</p> -->
    <RouterView/>
  </div>
</template>
