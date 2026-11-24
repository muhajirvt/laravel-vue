<template>
  <div class="min-h-screen bg-slate-50">
    <DashboardSidebar />
    <div class="lg:pl-64">
      <DashboardHeader :admin-user="adminUser" @logout="$emit('logout')" />
      <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import DashboardSidebar from "./DashboardSidebar.vue";
import DashboardHeader from "./DashboardHeader.vue";

defineProps({
  adminUser: {
    type: Object,
    default: null,
  },
});

defineEmits(["logout"]);
</script>
