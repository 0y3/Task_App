<script setup lang="ts">
import { reactive, ref } from "vue";

interface MenuItem {
    id: number;
    title: string;
    icon?: string;
    route?: string;
    children?: MenuItem[];
    isOpen?: boolean;
}

interface User {
    name: string;
    profileImage: string;
    roles: string[];
}

const user = reactive<User>({
    name: "Admin name",
    profileImage: "/images/default.jpg",
    roles: ["Admin", "Staff"],
});

const sidebarMenus = ref<MenuItem[]>([
    {
        id: 1,
        title: "Dashboard",
        route: "/dashboard",
        icon: "icon-home4",
    },
    {
        id: 2,
        title: "Users",
        icon: "icon-users",
        children: [
            { id: 21, title: "All Users", route: "/users" },
            { id: 22, title: "Roles", route: "/roles" },
        ],
        isOpen: false,
    },
]);

// Toggle submenu
function toggleMenu(menu: MenuItem) {
  menu.isOpen = !menu.isOpen;
}
</script>

<template>
    <div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">
        <!-- Sidebar user -->
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <!-- <img
                        :src="user.profileImage"
                        width="38"
                        height="38"
                        class="rounded-circle mr-3"
                        :alt="user.name"
                    /> -->
                    <font-awesome-icon icon="fa-regular fa-circle-user" size="2xl" class="mr-3" />
                    <div class="media-body">
                        <div class="font-weight-semibold">{{ user.name }}</div>
                        <div class="font-size-xs opacity-50">Active</div>
                    </div>

                    <!--(Settings) -->
                    <div class="ml-3 align-self-center">
                        <router-link to="/" class="text-white">
                            <font-awesome-icon icon="fa-solid fa-gears" spin/>
                        </router-link>
                    </div>

                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <li class="nav-item-header">
                    <span>Main</span>
                </li>

                <li class="nav-item">
                    <router-link to="/" class="nav-link">
                         <font-awesome-icon icon="fa-brands fa-threads" />
                        <span>My Task</span>
                    </router-link>
                </li>

                <!-- Dynamic menus -->
                <template v-for="menu in sidebarMenus" :key="menu.id">
                    <li
                        :class="[
                            'nav-item',
                            { 'nav-item-submenu': menu.children, 'nav-item-open': menu.isOpen }
                        ]"
                    >
                        <router-link
                            v-if="!menu.children"
                            :to="menu.route"
                            class="nav-link"
                        >
                            <font-awesome-icon icon="fa-regular fa-house" />
                            <span>{{ menu.title }}</span>
                        </router-link>

                        <!-- Parent Item-->
                        <a v-else href="#" class="nav-link" @click.prevent="menu.children ? toggleMenu(menu): null">
                            <font-awesome-icon icon="fa-solid fa-cog" spin />
                            <span>{{ menu.title }}</span>
                            <!-- <font-awesome-icon icon="fa-solid fa-chevron-right" /> -->
                        </a>

                        <!-- Submenu -->
                        <transition name="slide">
                            <ul v-if="menu.children && menu.isOpen" class="nav nav-group-sub">
                                <li
                                    class="nav-item"
                                    v-for="child in menu.children"
                                    :key="child.id"
                                >
                                    <router-link
                                        :to="child.route"
                                        class="nav-link"
                                        >{{ child.title }}
                                    </router-link>
                                </li>
                            </ul>
                        </transition>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</template>

<style scoped>
.profileImage {
    object-fit: cover;
}

/* Submenu shown when parent has nav-item-open */
.nav-item-submenu.nav-item-open > .nav-group-sub{
  display:block;
}
/* Submenu base style */
.nav-item-submenu > .nav-group-sub{
  padding-left:1.0rem;
  overflow:hidden; /* Needed for slide effect */
}
/* Slide animation */
.slide-enter-from, .slide-leave-to {
  max-height: 0;
  opacity: 0;
}
.slide-enter-to, .slide-leave-from {
  max-height: 500px; /* Max submenu height */
  opacity: 1;
}
.slide-enter-active, .slide-leave-active {
  transition: all 1.3s ease;
}
</style>
