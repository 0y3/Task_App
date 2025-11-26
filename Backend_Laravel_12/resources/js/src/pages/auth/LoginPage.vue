<script setup lang="ts">
import { ref } from "vue";
import { login } from "@/api/auth";
import type { LoginPayload } from "@/types/auth";
import { showSuccessToast, showErrorToast } from "@/utils/toast-notification";
import { useVuelidate } from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";
import Error from "@/components/Error.vue";
import BaseInput from "@/components/BaseInput.vue";
import BaseBtn from "@/components/BaseBtn.vue";

const form = ref<LoginPayload>({
    email: "",
    password: "",
    remember: false,
});
const loading = ref(false);
const v$ = useVuelidate(
    {
        email: { required, email },
        password: { required },
    },
    form
);

async function submit() {
    try {
        await v$.value.$validate();
        if (v$.value.$invalid) {
            showErrorToast("Please fix the validation errors.");
            return;
        }
        loading.value = true;
        const data = await login(form.value);
        v$.value.$reset();
        showSuccessToast(`Welcome ${data.data?.name ?? "User"}!`);
    } catch (e: any) {
        showErrorToast(e.response?.data?.message ?? "Login failed");
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <div class="bg-gray-100 min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-xl shadow-md">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900">Welcome back</h1>
                <p class="mt-2 text-gray-600">Sign in to your account</p>
            </div>
            <form @submit.prevent="submit" class="mt-8 space-y-6">
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>

                        <Error :errors="v$.email.$errors">
                            <BaseInput v-model="form.email" type="email" placeholder="Enter your email" />
                            <!-- <input
                                v-model="form.email"
                                id="email"
                                name="email"
                                type="email"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            /> -->
                        </Error>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <Error :errors="v$.password.$errors">
                            <BaseInput v-model="form.password" type="password" placeholder="Enter your password" />
                            <!-- <input
                            v-model="form.password"
                            id="password"
                            name="password"
                            type="password"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        /> -->
                        </Error>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <BaseInput v-model="form.remember" type="checkbox"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                        <!-- <input
                            id="remember-me"
                            name="remember-me"
                            type="checkbox"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                        /> -->
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot password?</a>
                    </div>
                </div>
                <div>
                    <BaseBtn :loading="loading" label="Sign in" />
                    <!--
                    <button
                        type="submit"
                        :disabled="loading"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <span
                            v-if="loading"
                            class="inline-block size-4 border-2 border-white border-t-transparent rounded-full animate-spin"
                        ></span>
                        <span v-else>Sign in</span>
                    </button>
                    -->
                </div>
            </form>
            <div class="text-center text-sm text-gray-600">
                Don't have an account?
                <a href="/register" class="font-medium text-indigo-600 hover:text-indigo-500">Sign up</a>
            </div>
        </div>
    </div>
</template>
