<template>
    <div class="min-h-screen bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 p-8">
        <h1 class="text-4xl font-bold mb-4">Dark Mode</h1>
        <button
            class="px-4 py-2 rounded bg-blue-600 text-white dark:bg-yellow-400 dark:text-gray-900"
            @click="toggleDark"
        >
            Toggle Dark Mode
        </button>
    </div>
</template>

<script setup lang="ts">
    function toggleDark() {
        document.documentElement.classList.toggle('dark')
    }
</script>
