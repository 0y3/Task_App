<script setup lang="ts">
    import { ref } from 'vue';

    // Current year dynamically
    const currentYear = new Date().getFullYear();
</script>

<template>
  <!-- Footer -->
  <div class="navbar navbar-expand-lg navbar-light">
    <!-- Mobile toggler -->
    <div class="text-center d-lg-none w-100">
      <button 
        type="button" 
        class="navbar-toggler dropdown-toggle" 
        data-bs-toggle="collapse" 
        data-bs-target="#navbar-footer"
      >
        <font-awesome-icon icon="fa-brands fa-stumbleupon-circle" class="mr-2" />
        Footer
      </button>
    </div>

    <!-- Collapsible content -->
    <div class="navbar-collapse collapse" id="navbar-footer">
      <span class="navbar-text">
        &copy; {{ currentYear }} <a href="#">Trovolink solution</a> by 
        <a href="" target="_blank">0y3</a>
      </span>
    </div>
  </div>
  <!-- /footer -->
</template>

<style scoped>
/* .navbar-text a {
  text-decoration: none;
  color: inherit;
}

.navbar-text a:hover {
  text-decoration: underline;
} */
</style>
