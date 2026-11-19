<template>
  <main class="min-h-screen bg-white lg:grid lg:grid-cols-[1.05fr_0.95fr]">
    <section class="relative hidden min-h-screen flex-col overflow-hidden bg-slate-950 px-12 py-10 text-white lg:flex xl:px-16">
      <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-indigo-500/20 blur-3xl"></div>
      <div class="pointer-events-none absolute -bottom-40 -left-24 h-96 w-96 rounded-full bg-violet-500/15 blur-3xl"></div>

      <a href="/login" class="relative flex w-fit items-center gap-3">
        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-500 shadow-lg shadow-indigo-950/40">
          <svg viewBox="0 0 24 24" fill="none" class="h-6 w-6 text-white" aria-hidden="true">
            <path d="M12 3 19 6v5c0 4.7-2.9 8.1-7 10-4.1-1.9-7-5.3-7-10V6l7-3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
            <path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </span>
        <span>
          <span class="block text-sm font-semibold tracking-wide">Admin workspace</span>
          <span class="mt-0.5 block text-xs text-slate-400">Account management</span>
        </span>
      </a>

      <div class="relative my-auto max-w-xl py-16">
        <span class="inline-flex items-center gap-2 rounded-full border border-indigo-300/20 bg-indigo-300/10 px-3 py-1.5 text-xs font-medium text-indigo-200">
          <span class="h-1.5 w-1.5 rounded-full bg-indigo-300"></span>
          Superadmin portal
        </span>
        <h1 class="mt-7 text-5xl font-bold leading-[1.12] tracking-tight xl:text-6xl">
          A clearer view of your
          <span class="bg-gradient-to-r from-indigo-300 to-violet-300 bg-clip-text text-transparent">workspace.</span>
        </h1>
        <p class="mt-6 max-w-lg text-base leading-7 text-slate-400">
          Manage user accounts from one secure place. Sign in with your superadmin credentials to continue.
        </p>

        <div class="mt-10 space-y-4">
          <div class="flex items-center gap-3 text-sm text-slate-300">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/[0.07] text-indigo-300">
              <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                <path d="M7 10V7a5 5 0 0 1 10 0v3m-11 0h12a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </span>
            Protected access for administrators
          </div>
          <div class="flex items-center gap-3 text-sm text-slate-300">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/[0.07] text-indigo-300">
              <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                <path d="M16 20v-1.5a3.5 3.5 0 0 0-3.5-3.5h-5A3.5 3.5 0 0 0 4 18.5V20m6-9a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm8-5h4m-2-2v4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </span>
            Simple user account management
          </div>
        </div>
      </div>

      <p class="relative text-xs text-slate-500">Secure administration workspace</p>
    </section>

    <section class="flex min-h-screen flex-col bg-white px-5 py-7 sm:px-8 lg:px-12">
      <div class="flex items-center gap-3 lg:hidden">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-sm font-bold text-white">A</span>
        <span>
          <span class="block text-sm font-semibold text-slate-900">Admin workspace</span>
          <span class="block text-xs text-slate-500">Superadmin portal</span>
        </span>
      </div>

      <div class="mx-auto my-auto w-full max-w-md py-12">
        <div class="mb-8 flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
          <svg viewBox="0 0 24 24" fill="none" class="h-7 w-7" aria-hidden="true">
            <path d="M12 3 19 6v5c0 4.7-2.9 8.1-7 10-4.1-1.9-7-5.3-7-10V6l7-3Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
            <path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </div>

        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-indigo-600">Administrator sign in</p>
        <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Welcome back</h2>
        <p class="mt-2 text-sm leading-6 text-slate-500">Enter your credentials to access the dashboard.</p>

        <form class="mt-8 space-y-5" @submit.prevent="login">
          <div>
            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email address</label>
            <div class="relative">
              <svg viewBox="0 0 24 24" fill="none" class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" aria-hidden="true">
                <path d="M4 6.5h16v11H4v-11Zm1 1 7 5.5 7-5.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <input
                id="email"
                v-model="form.email"
                type="email"
                autocomplete="username"
                required
                autofocus
                placeholder="admin@example.com"
                class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50"
              />
            </div>
          </div>

          <div>
            <div class="mb-2 flex items-center justify-between">
              <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
            </div>
            <div class="relative">
              <svg viewBox="0 0 24 24" fill="none" class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" aria-hidden="true">
                <path d="M7 10V7a5 5 0 0 1 10 0v3m-11 0h12a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Zm6 4v3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <input
                id="password"
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                autocomplete="current-password"
                required
                placeholder="Enter your password"
                class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-12 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50"
              />
              <button
                type="button"
                :aria-label="showPassword ? 'Hide password' : 'Show password'"
                class="absolute right-3 top-1/2 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600"
                @click="showPassword = !showPassword"
              >
                <svg v-if="showPassword" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                  <path d="M3 3l18 18M10.6 10.7a2 2 0 0 0 2.7 2.7M9.9 5.2A10.7 10.7 0 0 1 12 5c5 0 8.5 4.5 9.5 7-.4 1-1.2 2.2-2.3 3.2M6.2 6.3C4.2 7.6 2.9 9.6 2.5 12c.5 1.3 1.8 3.2 3.7 4.8A9.2 9.2 0 0 0 12 19c1.2 0 2.3-.2 3.3-.6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <svg v-else viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                  <path d="M2.5 12S6 5 12 5s9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                  <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6" />
                </svg>
              </button>
            </div>
          </div>

          <div v-if="errorMessage" class="flex items-start gap-2.5 rounded-xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
            <svg viewBox="0 0 24 24" fill="none" class="mt-0.5 h-4 w-4 shrink-0" aria-hidden="true">
              <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.7" />
              <path d="M12 8v5m0 3h.01" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
            </svg>
            {{ errorMessage }}
          </div>

          <button
            type="submit"
            :disabled="isSubmitting"
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm shadow-indigo-200 transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-100 disabled:cursor-not-allowed disabled:opacity-60"
          >
            <span v-if="isSubmitting" class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
            {{ isSubmitting ? "Signing you in..." : "Sign in to dashboard" }}
            <svg v-if="!isSubmitting" viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
              <path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>
        </form>

        <div class="mt-8 flex items-center justify-center gap-2 border-t border-slate-100 pt-5 text-xs text-slate-400">
          <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
            <path d="M7 10V7a5 5 0 0 1 10 0v3m-11 0h12a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          Protected superadmin access
        </div>
      </div>

      <p class="text-center text-xs text-slate-400">Admin workspace · Secure account management</p>
    </section>
  </main>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

const emit = defineEmits(["authenticated"]);
const form = ref({ email: "", password: "" });
const errorMessage = ref("");
const isSubmitting = ref(false);
const showPassword = ref(false);

const login = async () => {
  errorMessage.value = "";
  isSubmitting.value = true;

  try {
    const response = await axios.post("/api/auth/login", form.value);
    emit("authenticated", response.data.access_token);
  } catch (error) {
    errorMessage.value = error.response?.data?.message || "Unable to sign in. Please try again.";
  } finally {
    isSubmitting.value = false;
  }
};
</script>
