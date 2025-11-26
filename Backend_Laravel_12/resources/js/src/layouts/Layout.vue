<script setup lang="ts">
import Header from "./header/Header.vue";
import Aside from "@/layouts/aside/Aside.vue";
import Footer from "@/layouts/footer/Footer.vue";


import { ref, onMounted } from "vue";

const spinnerVisible = ref(false);
// State for the spinner/loader
const isLoading = ref(true)
const isSidebarOpen = ref(true)


const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value
}

onMounted(() => {
    // Example of replacing jQuery logic
    document.addEventListener("click", (e) => {
        if (e.target.closest(".page-title .icon-arrow-left52")) {
            window.history.back();
        }
    });

    setTimeout(() => {
        isLoading.value = false
    }, 800)
});
</script>

<template>
    <div>
        <!-- Spinner -->
        <div v-show="spinnerVisible" id="spinner" class="pace-demo">
            <div class="theme_xbox">
                <div
                    class="pace_progress"
                    data-progress-text="60%"
                    data-progress="60"
                ></div>
                <div class="pace_activity"></div>
            </div>
        </div>
        <!-- Header -->
        <Header @toggle-sidebar="toggleSidebar"/>

        <!-- Page content -->
        <div class="page-content">
            <!-- Sidebar -->
            <Aside />

            <!-- Main content -->
            <div class="content-wrapper">
                <!-- Main View  View Content -->
                
                <router-view v-slot="{ Component }">
                    <transition name="fade" mode="out-in">
                        <component :is="Component" />
                    </transition>
                </router-view>

                <!-- Footer -->
                <Footer />
            </div>
        </div>
    </div>
</template>


<style scoped>
/* Spinner */
#spinner {
    z-index: 1051;
    background-color: rgba(255, 255, 255, 0.7);
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    display: none;
}
#spinner::after {
    content: "";
    z-index: 1051;
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    display: block;
}
.theme_xbox .pace_activity,
.theme_xbox .pace_activity:after,
.theme_xbox .pace_activity:before {
    border-top-color: #89c40f;
}
.page-title .icon-arrow-left52 {
    cursor: pointer;
}
.page-title {
    text-transform: uppercase;
}

.fade-enter-active,.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,.fade-leave-to {
  opacity: 0;
}
</style>
