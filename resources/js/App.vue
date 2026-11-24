<template>
  <main>
    <LoadingScreen v-if="isChecking" />

    <Login v-else-if="!isAuthenticated" @authenticated="authenticate" />

    <DashboardShell v-else :admin-user="adminUser" @logout="logout">
      <Users @logout="clearSession" />
    </DashboardShell>
  </main>
</template>

<script setup>
import { onMounted } from "vue";
import DashboardShell from "./Components/DashboardShell.vue";
import LoadingScreen from "./Components/LoadingScreen.vue";
import Login from "./Components/Login.vue";
import Users from "./Components/Users.vue";
import { useAuthSession } from "./composables/useAuthSession";

const {
  isAuthenticated,
  isChecking,
  adminUser,
  authenticate,
  checkSession,
  clearSession,
  logout,
} = useAuthSession();

onMounted(checkSession);
</script>
