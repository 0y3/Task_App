<script lang="ts" setup>
import { ErrorObject } from "@vuelidate/core";

const props = defineProps<{
    errors: ErrorObject[];
}>();
</script>
<template>
    <!-- <label for="">{{ props.label }}</label> -->
    <div :class="{ error: props.errors.length }">
        <slot />
        <div
            v-for="error of errors"
            :key="error.$uid"
            class="flex items-start gap-2 text-sm text-red-700 bg-red-50 rounded border border-red-200 px-3 py-1"
        >
            <svg
                class="w-4 h-4 flex-shrink-0 mt-0.5"
                viewBox="0 0 20 20"
                fill="currentColor"
                aria-hidden="true"
            >
                <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-10.75a.75.75 0 10-1.5 0v3.5a.75.75 0 001.5 0v-3.5zM10 14a.9.9 0 110-1.8.9.9 0 010 1.8z"
                    clip-rule="evenodd"
                />
            </svg>
            <div class="leading-tight">{{ error.$message }}</div>
        </div>
    </div>
</template>
