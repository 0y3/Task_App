<script setup lang="ts">
import { ref } from 'vue';
</script>

<template>
    <div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

        <!-- Sidebar mobile toggler -->
        <div class="sidebar-mobile-toggler text-center">
            <a href="#" class="sidebar-mobile-main-toggle">
                <i class="icon-arrow-left8"></i>
            </a>
            Navigation
            <a href="#" class="sidebar-mobile-expand">
                <i class="icon-screen-full"></i>
                <i class="icon-screen-normal"></i>
            </a>
        </div>

        <!-- Sidebar content -->
        <div class="sidebar-content">

            <!-- User menu -->
            <div class="sidebar-user">
                <div class="card-body">
                    <div class="media">
                        <div class="mr-3">
                            <img
                                :src="user.profileImage"
                                width="38"
                                height="38"
                                class="rounded-circle profileImage"
                                alt="User"
                            />
                        </div>

                        <div class="media-body">
                            <div class="media-title font-weight-semibold">
                                {{ user.name }}
                            </div>
                            <div class="font-size-xs opacity-50">Active</div>
                        </div>

                        <div class="ml-3 align-self-center">
                            <a href="#" class="text-white"><i class="icon-cog3"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /user menu -->


            <!-- Main navigation -->
            <div class="card card-sidebar-mobile">
                <ul class="nav nav-sidebar" data-nav-type="accordion">

                    <li class="nav-item-header">
                        <div class="text-uppercase font-size-xs line-height-xs">Main</div>
                        <i class="icon-menu" title="Main"></i>
                    </li>

                    <!-- Example static item -->
                    <li class="nav-item">
                        <router-link to="/" class="nav-link">
                            <i class="icon-home4"></i>
                            <span>My Task</span>
                        </router-link>
                    </li>

                    <!-- Dynamic menus -->
                    <template v-for="(menu, index) in filteredSidebarMenus" :key="index">

                        <li
                            class="nav-item"
                            :class="{ 'nav-item-submenu': menu.children && menu.children.length }"
                        >
                            <router-link
                                v-if="!menu.children || menu.children.length === 0"
                                :to="menu.route"
                                class="nav-link"
                            >
                                <i class="icon-menu3"></i>
                                <span>{{ menu.name }}</span>
                            </router-link>

                            <a
                                v-else
                                href="#"
                                class="nav-link"
                            >
                                <i class="icon-menu3"></i>
                                <span>{{ menu.name }}</span>
                            </a>

                            <!-- Submenus -->
                            <ul v-if="menu.children && menu.children.length"
                                class="nav nav-group-sub"
                            >
                                <li
                                    class="nav-item"
                                    v-for="(child, cIndex) in filterByRole(childMenus(menu.children))"
                                    :key="cIndex"
                                >
                                    <router-link
                                        :to="child.route"
                                        class="nav-link"
                                    >
                                        {{ child.name }}
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                    </template>

                </ul>
            </div>
            <!-- /main navigation -->

        </div>
    </div>
</template>

<style lang="">
    
</style>