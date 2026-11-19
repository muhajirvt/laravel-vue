<template>
  <main>
    <div v-if="isChecking" class="flex min-h-screen items-center justify-center bg-slate-50">
      <div class="flex items-center gap-3 text-sm font-medium text-slate-500">
        <span class="h-5 w-5 animate-spin rounded-full border-2 border-slate-200 border-t-indigo-600"></span>
        Checking your session...
      </div>
    </div>

    <Login v-else-if="!isAuthenticated" @authenticated="authenticate" />

    <div v-else class="min-h-screen bg-slate-50">
      <aside class="fixed inset-y-0 left-0 z-30 hidden w-64 flex-col bg-slate-950 text-white lg:flex">
        <a href="/dashboard" class="flex items-center gap-3 border-b border-white/10 px-6 py-6">
          <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500 text-lg font-bold shadow-lg shadow-indigo-950/30">
            A
          </span>
          <span>
            <span class="block text-sm font-semibold tracking-wide">Admin workspace</span>
            <span class="mt-0.5 block text-xs text-slate-400">Account management</span>
          </span>
        </a>

        <nav class="flex-1 px-4 py-7">
          <p class="px-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Workspace</p>
          <a
            href="/dashboard"
            class="mt-3 flex items-center gap-3 rounded-xl bg-indigo-500/15 px-3 py-3 text-sm font-medium text-indigo-200"
          >
            <i class="fas fa-gauge-high w-5 text-center"></i>
            Dashboard
          </a>
        </nav>

        <div class="m-4 rounded-2xl border border-white/10 bg-white/[0.04] p-4">
          <div class="flex items-center gap-2 text-xs font-medium text-emerald-300">
            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
            Secure admin session
          </div>
          <p class="mt-2 text-xs leading-5 text-slate-400">User management is available to superadmins only.</p>
        </div>
      </aside>

      <div class="lg:pl-64">
        <header class="sticky top-0 z-20 border-b border-slate-200/80 bg-white/90 backdrop-blur">
          <div class="mx-auto flex h-[76px] max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
              <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-sm font-bold text-white lg:hidden">A</span>
              <div>
                <p class="text-sm font-semibold text-slate-900">Dashboard</p>
                <p class="hidden text-xs text-slate-500 sm:block">Manage your workspace accounts</p>
              </div>
            </div>

            <div class="flex items-center gap-3 sm:gap-4">
              <div class="hidden text-right sm:block">
                <p class="text-sm font-semibold text-slate-800">{{ adminUser?.name || "Superadmin" }}</p>
                <p class="text-xs text-slate-500">{{ adminUser?.email }}</p>
              </div>
              <span class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700">
                {{ adminInitials }}
              </span>
              <button
                type="button"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700"
                @click="logout"
              >
                <i class="fas fa-arrow-right-from-bracket"></i>
                <span class="hidden sm:inline">Sign out</span>
              </button>
            </div>
          </div>
        </header>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
          <Users @logout="clearSession" />
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import axios from "axios";
import Login from "./Components/Login.vue";
import Users from "./Components/Users.vue";

const isAuthenticated = ref(false);
const isChecking = ref(true);
const adminUser = ref(null);
const loginPath = "/login";
const dashboardPath = "/dashboard";

const adminInitials = computed(() => {
  const name = adminUser.value?.name?.trim();
  return name
    ? name.split(/\s+/).slice(0, 2).map((part) => part[0]).join("").toUpperCase()
    : "SA";
});

const redirectTo = (path) => {
  if (window.location.pathname !== path) {
    window.location.replace(path);
  }
};

const getStoredToken = () => {
  const savedToken = localStorage.getItem("access_token");
  if (savedToken) return savedToken;

  const sessionToken = sessionStorage.getItem("access_token");
  if (sessionToken) {
    localStorage.setItem("access_token", sessionToken);
    sessionStorage.removeItem("access_token");
  }

  return sessionToken;
};

const applyToken = (token) => {
  localStorage.setItem("access_token", token);
  sessionStorage.removeItem("access_token");
  axios.defaults.headers.common.Authorization = `Bearer ${token}`;
  isAuthenticated.value = true;
};

const clearSession = () => {
  localStorage.removeItem("access_token");
  sessionStorage.removeItem("access_token");
  delete axios.defaults.headers.common.Authorization;
  adminUser.value = null;
  isAuthenticated.value = false;
  redirectTo(loginPath);
};

const authenticate = (token) => {
  applyToken(token);
  redirectTo(dashboardPath);
};

const logout = async () => {
  try {
    await axios.post("/api/auth/logout");
  } catch {
    window.alert("The server could not revoke this token. Your local session has been cleared.");
  } finally {
    clearSession();
  }
};

onMounted(async () => {
  const token = getStoredToken();

  if (!token) {
    isChecking.value = false;
    redirectTo(loginPath);
    return;
  }

  applyToken(token);

  try {
    const response = await axios.get("/api/auth/current-user");
    adminUser.value = response.data.data;
  } catch {
    clearSession();
    isChecking.value = false;
    return;
  }

  redirectTo(dashboardPath);
  isChecking.value = false;
});
</script>
