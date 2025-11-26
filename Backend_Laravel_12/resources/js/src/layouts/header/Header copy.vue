<script lang="ts">
import { ref } from 'vue';

// Emit event to parent layout
const emit = defineEmits<{
  (e: 'toggle-sidebar'): void
}>()

// User dropdown state
const isUserDropdownOpen = ref(false)
const toggleUserDropdown = () => {
  isUserDropdownOpen.value = !isUserDropdownOpen.value
}
</script>

<template>
  <!-- Main navbar -->
  <nav class="navbar navbar-expand-md navbar-light bg-light">
    <!-- Header with logos -->
    <div class="navbar-brand d-none d-md-flex align-items-center">
      <router-link to="/" class="d-inline-block text-dark fw-bold" style="font-size:20px">
        Project Management System
      </router-link>
    </div>

    <!-- Mobile controls -->
    <div class="d-flex flex-1 d-md-none">
      <button class="btn btn-light me-2" @click="emit('toggle-sidebar')">
        <i class="icon-paragraph-justify3"></i>
      </button>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-mobile">
        <i class="icon-tree5"></i>
      </button>
    </div>

    <!-- Navbar content -->
    <div class="collapse navbar-collapse" id="navbar-mobile">
      <ul class="navbar-nav me-auto">
        <li class="nav-item d-none d-md-block">
          <button class="btn btn-light" @click="emit('toggle-sidebar')">
            <i class="icon-paragraph-justify3"></i>
          </button>
        </li>
      </ul>

      <ul class="navbar-nav ms-auto">
        <!-- User dropdown -->
        <li class="nav-item dropdown">
          <button class="btn nav-link d-flex align-items-center" @click="toggleUserDropdown">
            <img src="https://ui-avatars.com/api/?name=Maximus+Douglas&background=random"
                 class="rounded-circle me-2" height="34" alt=""/>
            <span>My name</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" :class="{ show: isUserDropdownOpen }">
            <li>
              <router-link to="/" class="dropdown-item">
                <i class="icon-user-plus"></i> My profile
              </router-link>
            </li>
            <li><hr class="dropdown-divider"/></li>
            <li>
              <router-link to="/logout" class="dropdown-item">
                <i class="icon-switch2"></i> Logout
              </router-link>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</template>

<style scoped>
/* Optional: adjust dropdown to work without Bootstrap JS */
.dropdown-menu.show {
  display: block;
}
</style>
