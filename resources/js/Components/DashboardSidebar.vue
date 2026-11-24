<template>
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
</template>
