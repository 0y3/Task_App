<script lang="ts" setup>
const props = defineProps<{
    type?: "text" | "password" | "email" | "number" | "checkbox" | "radio";
    modelValue?: string | boolean | number | null;
    placeholder?: string;
    name?: string;
    class?: string;
}>();

const emits = defineEmits<{
    (e: "update:modelValue", value: string | boolean | number): void;
}>();
function emitUpdateModelValue(event: Event) {
    // Safely read value from the event target (handles possible null)
    const target = event.target as HTMLInputElement | null;
    if (!target) return;
    let value: string | boolean | number | null;

    switch (props.type) {
        case "checkbox":
        case "radio":
            value = target.checked;
            break;
        case "number":
            value = target.value ? Number(target.value) : 0;
            break;
        default:
            value = target.value;
    }
    emits("update:modelValue", value);
    // console.log(value);
}
</script>

<template>
    <input
        :type="props.type || 'text'"
        :value="props.type === 'checkbox' || props.type === 'radio'  ? undefined : props.modelValue"
        :checked="props.type === 'checkbox' || props.type === 'radio' ? Boolean(props.modelValue) : undefined"
        :name="props.name"
        :placeholder="props.placeholder || ''"
        @input="emitUpdateModelValue"
        @change="emitUpdateModelValue"
        :class="
            props.class ||
            'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500'
        "
    />
</template>
