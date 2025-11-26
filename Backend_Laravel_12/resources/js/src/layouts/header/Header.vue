<script lang="ts">
import { reactive,ref } from 'vue';

interface User {
    name: string;
    profileImage: string;
}
// Example user data
const user = reactive<User>({
    name: "Admin name",
    profileImage: "/images/default.jpg",
});

</script>

<template>
    <!-- Main navbar -->
    <div class="navbar navbar-expand-md navbar-light">
        <!-- Header with logos -->
         <div class="navbar-header navbar-dark d-none d-md-flex align-items-md-center">
            <div class="navbar-brand navbar-brand-md">
                <router-link to="/" class="d-inline-block">
                <span class="text-white text-bold" style="font-size:16px;font-weight:700;">
                    Project Management System
                </span>
                </router-link>
            </div>

            <div class="navbar-brand navbar-brand-xs">
                <router-link to="/" class="d-inline-block">
                <span class="text-white">Project Management System</span>
                </router-link>
            </div>
        </div>
        <!-- /header with logos -->

        <!-- Mobile controls -->
        <div class="d-flex flex-1 d-md-none">
            <div class="navbar-brand mr-auto">
                <router-link to="/" class="d-inline-block"></router-link>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-mobile">
                <i class="icon-tree5"></i>
            </button>

            <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
                <i class="icon-paragraph-justify3"></i>
            </button>
        </div>
        <!-- /mobile controls -->

        <!-- Navbar content -->
        <div class="collapsee navbar-collapse" id="navbar-mobile">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                        <font-awesome-icon icon="fa-solid fa-bars" size="xl" />
                    </a>
                </li>
            </ul>

            <span class="badge bg-pink-400 badge-pill ml-md-3 mr-md-auto" style="visibility:visible;">16 Task</span>

            <ul class="navbar-nav">
                <li class="nav-item dropdown dropdown-user">
                    <a 
                        href="#" 
                        class="navbar-nav-link d-flex align-items-center dropdown-toggle" 
                        role="button" 
                        data-bs-toggle="dropdown"
                    >
                        <!-- <img :src="user.value.profileImage" class="rounded-circle mr-2 profileImage" height="34" alt="User"> -->
                        <font-awesome-icon icon="fa-regular fa-circle-user" size="2xl" class="mr-2 profileImage" />
                        <span>Admin Name</span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end">
                        <router-link to="/" class="dropdown-item">
                        <i class="icon-user-plus"></i> My profile
                        </router-link>
                        <div class="dropdown-divider"></div>
                        <router-link to="/logout" class="dropdown-item">
                        <i class="icon-switch2"></i> Logout
                        </router-link>
                    </div>
                </li>
            </ul>
        </div>
            <!-- /navbar content -->

    </div>
    <!-- /main navbar -->
</template>

<style scoped>
/* Optional: adjust dropdown to work without Bootstrap JS */
.dropdown-menu.show {
  display: block;
}
</style>
