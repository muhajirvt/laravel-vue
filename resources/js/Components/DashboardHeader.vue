<template>
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
          @click="$emit('logout')"
        >
          <i class="fas fa-arrow-right-from-bracket"></i>
          <span class="hidden sm:inline">Sign out</span>
        </button>
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  adminUser: {
    type: Object,
    default: null,
  },
});

defineEmits(["logout"]);

const adminInitials = computed(() => {
  const name = props.adminUser?.name?.trim();
  return name
    ? name.split(/\s+/).slice(0, 2).map((part) => part[0]).join("").toUpperCase()
    : "SA";
});
</script>
