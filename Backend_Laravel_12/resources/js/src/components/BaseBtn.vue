<script lang="ts" setup>
const props = defineProps<{
    type?: 'button' | 'submit' | 'reset';
    class?: string;
    loading?: boolean;
    label: string;
}>();

</script>

<template>
    <button
        :type="props.type ?? 'submit'"
        :disabled="props.loading"
        :class="props.class || 'w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'"
    >
        <span
            v-if="props.loading"
            class="inline-block size-4 border-2 border-white border-t-transparent rounded-full animate-spin"
        ></span>
        <span v-else>{{ props.label }}</span>
    </button>
</template>
