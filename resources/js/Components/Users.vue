<template>
  <div class="min-h-screen bg-gray-100 flex flex-col items-center py-8">
    <div class="bg-white shadow-lg rounded-lg w-full max-w-5xl p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">User Management</h2>
        <button
          @click="openAddModal"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
        >
          + Add User
        </button>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200">
          <thead class="bg-gray-200 text-gray-700 uppercase text-sm">
            <tr>
              <th class="py-2 px-3 border">#</th>
              <th class="py-2 px-3 border">Name</th>
              <th class="py-2 px-3 border">Email</th>
              <th class="py-2 px-3 border">Created At</th>
              <th class="py-2 px-3 border text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(user, index) in users"
              :key="user.id"
              class="border-b hover:bg-gray-50"
            >
              <td class="py-2 px-3 border text-center">
                {{ (pagination.current_page - 1) * pagination.per_page + index + 1 }}
              </td>
              <td class="py-2 px-3 border">{{ user.name }}</td>
              <td class="py-2 px-3 border">{{ user.email }}</td>
              <td class="py-2 px-3 border">
                {{ formatDate(user.created_at) }}
              </td>
              <td class="py-2 px-3 border text-center space-x-2">
                <button
                  @click="viewUser(user)"
                  class="text-green-500 hover:text-green-700"
                >
                  <i class="fas fa-eye"></i>
                </button>
                <button
                  @click="editUser(user)"
                  class="text-blue-500 hover:text-blue-700"
                >
                  <i class="fas fa-edit"></i>
                </button>
                <button
                  @click="confirmDelete(user)"
                  class="text-red-500 hover:text-red-700"
                >
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="flex justify-center mt-4 space-x-2">
        <button
          v-for="page in pagination.last_page"
          :key="page"
          @click="fetchUsers(page)"
          class="px-3 py-1 border rounded"
          :class="{
            'bg-blue-500 text-white': pagination.current_page === page,
            'hover:bg-gray-200': pagination.current_page !== page
          }"
        >
          {{ page }}
        </button>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
    >
      <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <h3 class="text-xl font-semibold mb-4">
          {{ isEditing ? "Edit User" : "Add User" }}
        </h3>

        <form @submit.prevent="isEditing ? updateUser() : addUser()">
          <div class="mb-4">
            <input
              type="text"
              v-model="form.name"
              placeholder="Name"
              class="border rounded w-full px-3 py-2"
            />
            <p v-if="formErrors.name" class="text-red-500 text-sm mt-1">
              {{ formErrors.name[0] }}
            </p>
          </div>

          <div class="mb-4">
            <input
              type="email"
              v-model="form.email"
              placeholder="Email"
              class="border rounded w-full px-3 py-2"
            />
            <p v-if="formErrors.email" class="text-red-500 text-sm mt-1">
              {{ formErrors.email[0] }}
            </p>
          </div>

          <div v-if="!isEditing" class="mb-4">
            <input
              type="password"
              v-model="form.password"
              placeholder="Password"
              class="border rounded w-full px-3 py-2"
            />
            <p v-if="formErrors.password" class="text-red-500 text-sm mt-1">
              {{ formErrors.password[0] }}
            </p>
          </div>

          <div class="flex justify-end space-x-3">
            <button
              type="button"
              @click="closeModal"
              class="px-4 py-2 border rounded hover:bg-gray-200"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
              {{ isEditing ? "Update" : "Save" }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- View Modal -->
    <div
      v-if="showViewModal"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
    >
      <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h3 class="text-xl font-semibold mb-4">User Details</h3>
        <p><strong>Name:</strong> {{ selectedUser.name }}</p>
        <p><strong>Email:</strong> {{ selectedUser.email }}</p>
        <p><strong>Created At:</strong> {{ formatDate(selectedUser.created_at) }}</p>
        <div class="flex justify-end mt-4">
          <button
            @click="closeViewModal"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import "@fortawesome/fontawesome-free/css/all.min.css";

const users = ref([]);
const pagination = ref({});
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
});

const fetchUsers = async (page = 1) => {
  try {
    const res = await axios.get(`/api/users?page=${page}`);
    if (res.status === 200 && res.data.status) {
      users.value = res.data.data;
      pagination.value = res.data.pagination;
    }
  } catch (error) {
    handleApiError(error);
  }
};

const addUser = async () => {
  try {
    const res = await axios.post("/api/users", form.value);
    if (res.status === 201) {
      Swal.fire("Success", res.data.message, "success");
      closeModal();
      fetchUsers(pagination.value.current_page);
    }
  } catch (error) {
    handleApiError(error);
  }
};

const updateUser = async () => {
  try {
    const res = await axios.put(`/api/users/${form.value.id}`, form.value);
    if (res.status === 200) {
      Swal.fire("Updated", res.data.message, "success");
      closeModal();
      fetchUsers(pagination.value.current_page);
    }
  } catch (error) {
    handleApiError(error);
  }
};

const confirmDelete = (user) => {
  Swal.fire({
    title: "Are you sure?",
    text: "You want to delete this user?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, Delete",
  }).then(async (result) => {
    if (result.isConfirmed) {
      await deleteUser(user.id);
    }
  });
};

const deleteUser = async (id) => {
  try {
    const res = await axios.delete(`/api/users/${id}`);
    if (res.status === 204) {
      Swal.fire("Deleted!", "User deleted successfully.", "success");
      fetchUsers(pagination.value.current_page);
    }
  } catch (error) {
    handleApiError(error);
  }
};

const handleApiError = (error) => {
  if (error.response) {
    const status = error.response.status;
    if (status === 422) {
      formErrors.value = error.response.data.errors || {};
    } else if (status === 500) {
      Swal.fire("Error", error.response.data.message, "error");
    } else {
      Swal.fire("Error", "Unexpected server response.", "error");
    }
  } else {
    Swal.fire("Error", "Network error, please try again.", "error");
  }
};

const viewUser = (user) => {
  selectedUser.value = user;
  showViewModal.value = true;
};

const editUser = (user) => {
  isEditing.value = true;
  form.value = { ...user };
  formErrors.value = {};
  showModal.value = true;
};

const openAddModal = () => {
  isEditing.value = false;
  form.value = { id: null, name: "", email: "", password: "" };
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

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleString();
};

onMounted(() => fetchUsers());
</script>
