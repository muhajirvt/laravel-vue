<template>
  <section id="user-management" class="space-y-7">
    <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
      <div>
        <p class="mb-2 text-xs font-semibold uppercase tracking-[0.18em] text-indigo-600">Workspace</p>
        <h1 class="text-3xl font-bold tracking-tight text-slate-950">User management</h1>
        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
          Create and manage the accounts that have access to your platform.
        </p>
      </div>
      <button
        type="button"
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm shadow-indigo-200 transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-100"
        @click="openAddModal"
      >
        <i class="fas fa-plus"></i>
        Add user
      </button>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
      <article class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div>
          <p class="text-sm font-medium text-slate-500">Registered users</p>
          <p class="mt-2 text-3xl font-bold tracking-tight text-slate-950">
            {{ pagination.total ?? 0 }}
          </p>
          <p class="mt-1 text-xs text-slate-400">Accounts in your workspace</p>
        </div>
        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-lg text-indigo-600">
          <i class="fas fa-users"></i>
        </span>
      </article>

      <article class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div>
          <p class="text-sm font-medium text-slate-500">Current page</p>
          <p class="mt-2 text-3xl font-bold tracking-tight text-slate-950">
            {{ pagination.current_page || 1 }}
            <span class="text-lg font-medium text-slate-400">/ {{ pagination.last_page || 1 }}</span>
          </p>
          <p class="mt-1 text-xs text-slate-400">
            {{ displayStart }}–{{ displayEnd }} of {{ pagination.total ?? 0 }} users
          </p>
        </div>
        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-lg text-emerald-600">
          <i class="fas fa-list-check"></i>
        </span>
      </article>
    </div>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div>
          <h2 class="text-base font-semibold text-slate-900">All users</h2>
          <p class="mt-1 text-sm text-slate-500">Review profile details and manage account records.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600">
          <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
          {{ pagination.total ?? 0 }} total
        </span>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-100">
          <thead class="bg-slate-50/80">
            <tr class="text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">
              <th class="px-5 py-3.5 sm:px-6">User</th>
              <th class="px-5 py-3.5">Date added</th>
              <th class="px-5 py-3.5 text-right sm:px-6">Actions</th>
            </tr>
          </thead>

          <tbody v-if="isLoading" class="divide-y divide-slate-100">
            <tr>
              <td colspan="3" class="px-6 py-16 text-center text-sm text-slate-500">
                <span class="inline-flex items-center gap-3">
                  <span class="h-5 w-5 animate-spin rounded-full border-2 border-slate-200 border-t-indigo-600"></span>
                  Loading users...
                </span>
              </td>
            </tr>
          </tbody>

          <tbody v-else-if="users.length" class="divide-y divide-slate-100">
            <tr v-for="user in users" :key="user.id" class="transition hover:bg-slate-50/70">
              <td class="whitespace-nowrap px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3.5">
                  <UserAvatar
                    v-if="user.avatar_url"
                    :src="user.avatar_url"
                    :alt="`${user.name}'s profile`"
                    @unauthorized="emit('logout')"
                  />
                  <span v-else class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-50 text-sm font-semibold text-indigo-600">
                    {{ userInitials(user.name) }}
                  </span>
                  <span>
                    <span class="block text-sm font-semibold text-slate-800">{{ user.name }}</span>
                    <span class="mt-0.5 block text-xs text-slate-500">{{ user.email }}</span>
                  </span>
                </div>
              </td>
              <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                {{ formatDate(user.created_at) }}
              </td>
              <td class="whitespace-nowrap px-5 py-4 text-right sm:px-6">
                <div class="inline-flex items-center gap-1">
                  <button
                    type="button"
                    :aria-label="`View ${user.name}`"
                    title="View user"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition hover:bg-indigo-50 hover:text-indigo-600"
                    @click="viewUser(user)"
                  >
                    <i class="fas fa-eye"></i>
                  </button>
                  <button
                    type="button"
                    :aria-label="`Edit ${user.name}`"
                    title="Edit user"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition hover:bg-indigo-50 hover:text-indigo-600"
                    @click="editUser(user)"
                  >
                    <i class="fas fa-pen"></i>
                  </button>
                  <button
                    type="button"
                    :aria-label="`Delete ${user.name}`"
                    title="Delete user"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition hover:bg-rose-50 hover:text-rose-600"
                    @click="confirmDelete(user)"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>

          <tbody v-else>
            <tr>
              <td colspan="3" class="px-6 py-16 text-center">
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-xl text-indigo-500">
                  <i class="fas fa-user-plus"></i>
                </span>
                <h3 class="mt-4 text-sm font-semibold text-slate-900">No users yet</h3>
                <p class="mt-1 text-sm text-slate-500">Create the first account to get started.</p>
                <button
                  type="button"
                  class="mt-5 rounded-lg px-3 py-2 text-sm font-semibold text-indigo-600 hover:bg-indigo-50"
                  @click="openAddModal"
                >
                  Add your first user
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="!isLoading && users.length" class="flex flex-col gap-3 border-t border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <p class="text-xs text-slate-500">
          Showing <span class="font-semibold text-slate-700">{{ displayStart }}–{{ displayEnd }}</span>
          of <span class="font-semibold text-slate-700">{{ pagination.total }}</span> users
        </p>
        <div class="flex items-center gap-2">
          <button
            type="button"
            :disabled="pagination.current_page <= 1"
            class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
            @click="fetchUsers(pagination.current_page - 1)"
          >
            <i class="fas fa-chevron-left mr-1"></i>
            Previous
          </button>
          <span class="px-2 text-xs font-medium text-slate-500">
            {{ pagination.current_page }} / {{ pagination.last_page }}
          </span>
          <button
            type="button"
            :disabled="pagination.current_page >= pagination.last_page"
            class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
            @click="fetchUsers(pagination.current_page + 1)"
          >
            Next
            <i class="fas fa-chevron-right ml-1"></i>
          </button>
        </div>
      </div>
    </section>

    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-950/60 p-4 backdrop-blur-sm"
      @click.self="closeModal"
    >
      <section class="my-auto w-full max-w-xl overflow-hidden rounded-2xl bg-white shadow-2xl">
        <header class="flex items-start justify-between border-b border-slate-100 px-6 py-5">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
              {{ isEditing ? "Account settings" : "New account" }}
            </p>
            <h2 class="mt-1 text-xl font-bold text-slate-900">
              {{ isEditing ? "Edit user" : "Add a user" }}
            </h2>
          </div>
          <button
            type="button"
            aria-label="Close"
            class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700"
            @click="closeModal"
          >
            <i class="fas fa-xmark"></i>
          </button>
        </header>

        <form class="space-y-5 px-6 py-6" @submit.prevent="isEditing ? updateUser() : addUser()">
          <div>
            <label for="user-name" class="mb-1.5 block text-sm font-medium text-slate-700">Full name</label>
            <input
              id="user-name"
              v-model="form.name"
              type="text"
              required
              autocomplete="name"
              placeholder="e.g. Alex Morgan"
              class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50"
            />
            <p v-if="formErrors.name" class="mt-1.5 text-xs text-rose-600">{{ formErrors.name[0] }}</p>
          </div>

          <div>
            <label for="user-email" class="mb-1.5 block text-sm font-medium text-slate-700">Email address</label>
            <input
              id="user-email"
              v-model="form.email"
              type="email"
              required
              autocomplete="email"
              placeholder="name@example.com"
              class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50"
            />
            <p v-if="formErrors.email" class="mt-1.5 text-xs text-rose-600">{{ formErrors.email[0] }}</p>
          </div>

          <div v-if="!isEditing">
            <label for="user-password" class="mb-1.5 block text-sm font-medium text-slate-700">Temporary password</label>
            <input
              id="user-password"
              v-model="form.password"
              type="password"
              required
              minlength="8"
              autocomplete="new-password"
              placeholder="At least 8 characters"
              class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50"
            />
            <p v-if="formErrors.password" class="mt-1.5 text-xs text-rose-600">{{ formErrors.password[0] }}</p>
          </div>

          <div>
            <label for="user-image" class="mb-1.5 block text-sm font-medium text-slate-700">
              Profile photo <span class="font-normal text-slate-400">(optional)</span>
            </label>
            <input
              id="user-image"
              type="file"
              accept="image/*"
              class="block w-full cursor-pointer rounded-xl border border-slate-200 text-sm text-slate-500 file:mr-4 file:border-0 file:bg-slate-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-100"
              @change="selectImage"
            />
            <p class="mt-1.5 text-xs text-slate-400">Image files up to 2 MB.</p>
            <p v-if="formErrors.image" class="mt-1.5 text-xs text-rose-600">{{ formErrors.image[0] }}</p>
          </div>

          <footer class="flex justify-end gap-3 border-t border-slate-100 pt-5">
            <button
              type="button"
              class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
              @click="closeModal"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="isSaving"
              class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-60"
            >
              {{ isSaving ? "Saving..." : isEditing ? "Save changes" : "Create user" }}
            </button>
          </footer>
        </form>
      </section>
    </div>

    <div
      v-if="showViewModal"
      class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-950/60 p-4 backdrop-blur-sm"
      @click.self="closeViewModal"
    >
      <section class="my-auto w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="relative bg-gradient-to-br from-indigo-600 to-violet-700 px-6 pb-7 pt-6 text-center text-white">
          <button
            type="button"
            aria-label="Close"
            class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-lg text-white/70 transition hover:bg-white/10 hover:text-white"
            @click="closeViewModal"
          >
            <i class="fas fa-xmark"></i>
          </button>
          <div class="mt-2 flex justify-center">
            <UserAvatar
              v-if="selectedUser.avatar_url"
              :src="selectedUser.avatar_url"
              :alt="`${selectedUser.name}'s profile`"
              size="large"
              @unauthorized="emit('logout')"
            />
            <span v-else class="flex h-32 w-32 items-center justify-center rounded-full border-4 border-white/20 bg-white/10 text-3xl font-bold">
              {{ userInitials(selectedUser.name) }}
            </span>
          </div>
          <h2 class="mt-4 text-xl font-bold">{{ selectedUser.name }}</h2>
          <p class="mt-1 text-sm text-indigo-100">{{ selectedUser.email }}</p>
        </div>
        <div class="space-y-4 px-6 py-5">
          <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
            <span class="text-sm text-slate-500">User ID</span>
            <span class="text-sm font-semibold text-slate-800">#{{ selectedUser.id }}</span>
          </div>
          <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
            <span class="text-sm text-slate-500">Date added</span>
            <span class="text-sm font-semibold text-slate-800">{{ formatDate(selectedUser.created_at) }}</span>
          </div>
          <button
            type="button"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
            @click="closeViewModal"
          >
            Close details
          </button>
        </div>
      </section>
    </div>
  </section>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import UserAvatar from "./UserAvatar.vue";
import "@fortawesome/fontawesome-free/css/all.min.css";

const emit = defineEmits(["logout"]);
const users = ref([]);
const pagination = ref({});
const isLoading = ref(true);
const isSaving = ref(false);
const showModal = ref(false);
const showViewModal = ref(false);
const isEditing = ref(false);
const selectedUser = ref({});
const formErrors = ref({});
const form = ref({
  id: null,
  name: "",
  email: "",
  password: "",
  image: null,
});

const displayStart = computed(() =>
  users.value.length ? (pagination.value.current_page - 1) * pagination.value.per_page + 1 : 0,
);
const displayEnd = computed(() =>
  users.value.length ? displayStart.value + users.value.length - 1 : 0,
);

const fetchUsers = async (page = 1) => {
  isLoading.value = true;
  try {
    const response = await axios.get(`/api/users?page=${page}`);
    users.value = response.data.data;
    pagination.value = response.data.pagination;
  } catch (error) {
    handleApiError(error);
  } finally {
    isLoading.value = false;
  }
};

const addUser = async () => {
  isSaving.value = true;
  try {
    const response = await axios.post("/api/users", toFormData());
    Swal.fire("User created", response.data.message, "success");
    closeModal();
    await fetchUsers(pagination.value.current_page || 1);
  } catch (error) {
    handleApiError(error);
  } finally {
    isSaving.value = false;
  }
};

const updateUser = async () => {
  isSaving.value = true;
  try {
    const payload = toFormData();
    payload.append("_method", "PUT");
    const response = await axios.post(`/api/users/${form.value.id}`, payload);
    Swal.fire("Changes saved", response.data.message, "success");
    closeModal();
    await fetchUsers(pagination.value.current_page || 1);
  } catch (error) {
    handleApiError(error);
  } finally {
    isSaving.value = false;
  }
};

const confirmDelete = (user) => {
  Swal.fire({
    title: "Delete this user?",
    text: `${user.name} will be removed from the workspace.`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Delete user",
    cancelButtonText: "Keep user",
    confirmButtonColor: "#e11d48",
  }).then(async (result) => {
    if (!result.isConfirmed) return;

    try {
      await axios.delete(`/api/users/${user.id}`);
      Swal.fire("User deleted", "The account was removed successfully.", "success");
      const page = users.value.length === 1 && pagination.value.current_page > 1
        ? pagination.value.current_page - 1
        : pagination.value.current_page;
      await fetchUsers(page);
    } catch (error) {
      handleApiError(error);
    }
  });
};

const handleApiError = (error) => {
  if (error.response?.status === 401) {
    emit("logout");
  } else if (error.response?.status === 422) {
    formErrors.value = error.response.data.errors || {};
  } else if (error.response) {
    Swal.fire("Request failed", error.response.data.message || "Please try again.", "error");
  } else {
    Swal.fire("Connection problem", "Could not reach the server. Check your connection and try again.", "error");
  }
};

const viewUser = (user) => {
  selectedUser.value = user;
  showViewModal.value = true;
};

const editUser = (user) => {
  isEditing.value = true;
  form.value = { ...user, password: "", image: null };
  formErrors.value = {};
  showModal.value = true;
};

const openAddModal = () => {
  isEditing.value = false;
  form.value = { id: null, name: "", email: "", password: "", image: null };
  formErrors.value = {};
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  formErrors.value = {};
};

const closeViewModal = () => {
  showViewModal.value = false;
};

const selectImage = (event) => {
  form.value.image = event.target.files?.[0] || null;
};

const toFormData = () => {
  const data = new FormData();
  data.append("name", form.value.name);
  data.append("email", form.value.email);

  if (!isEditing.value) {
    data.append("password", form.value.password);
  }

  if (form.value.image) {
    data.append("image", form.value.image);
  }

  return data;
};

const userInitials = (name = "") =>
  name.trim().split(/\s+/).slice(0, 2).map((part) => part[0]).join("").toUpperCase() || "U";

const formatDate = (dateString) =>
  dateString
    ? new Intl.DateTimeFormat(undefined, { dateStyle: "medium" }).format(new Date(dateString))
    : "—";

onMounted(() => fetchUsers());
</script>
