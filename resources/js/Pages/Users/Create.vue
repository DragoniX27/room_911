<script>
import ValidationErrors from "@/Components/ValidationErrors.vue";
import { defineComponent } from "vue";

export default defineComponent({
  components: {
    ValidationErrors,
  },
  props: {
    roles: {
      type: Object,
    },
    departments: {
      type: Object,
    },
  },
  data() {
    return {
      form: this.$inertia.form({
        name: "",
        email: "",
        password: "",
        phone: "",
        department_id: "",
        status: "active",
      }),
      role: "",
    };
  },
  methods: {
    //Function to put the phone format in a field
    formatAsPhoneNumber() {
      let num = this.form.phone.replace(/\D/g, ""); // solo dígitos
      if (num.length > 10) num = num.slice(0, 10);

      if (num.length >= 6) {
        this.form.phone = `(${num.slice(0, 3)}) ${num.slice(3, 6)}-${num.slice(6)}`;
      } else if (num.length >= 3) {
        this.form.phone = `(${num.slice(0, 3)}) ${num.slice(3)}`;
      } else if (num.length > 0) {
        this.form.phone = `(${num}`;
      }
    },
    submit() {
      this.$showLoading();
      this.$inertia.post(
        route("users.store"),
        { form: this.form, role: this.role },
        {
          onError: (errors) => {
            this.$hideLoading();
          },
        },
        {
          onSuccess: () => {
            this.$hideLoading();
          },
        }
      );
    },
  },
  mounted() {
    console.log(this.roles, this.departments);
  },
});
</script>
<template>
  <AppLayout title="Create User">
    <div class="bg-gray-100 mt-5 flex items-center justify-center">
      <form
        class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-lg space-y-3"
        @submit.prevent="submit"
      >
        <h2 class="text-2xl font-bold text-gray-800 text-center">Create User</h2>
        <ValidationErrors></ValidationErrors>
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700"
            >Full Name</label
          >
          <input
            type="text"
            id="name"
            name="name"
            v-model="form.name"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500"
          />
        </div>
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            type="email"
            id="email"
            name="email"
            v-model="form.email"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500"
          />
        </div>
        <div>
          <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
          <input
            type="text"
            id="phone"
            name="phone"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500"
            v-model="form.phone"
            @input="formatAsPhoneNumber"
          />
        </div>
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700"
            >Password</label
          >
          <input
            type="password"
            id="password"
            name="password"
            v-model="form.password"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500"
          />
        </div>
        <div class="space-y-2">
          <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
          <select
            id="status"
            name="status"
            v-model="form.status"
            class="block w-full px-4 py-2 border border-gray-300 bg-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="active">active</option>
            <option value="inactive">inactive</option>
          </select>
        </div>
        <div class="space-y-2">
          <label for="department" class="block text-sm font-medium text-gray-700"
            >Department</label
          >
          <select
            id="department"
            name="department"
            v-model="form.department_id"
            class="block w-full px-4 py-2 border border-gray-300 bg-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <template v-for="(department, d) in departments" :key="d">
              <option :value="department.id">{{ department.name }}</option>
            </template>
          </select>
        </div>
        <div class="space-y-2">
          <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
          <select
            id="role"
            name="role"
            v-model="role"
            class="block w-full px-4 py-2 border border-gray-300 bg-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <template v-for="(rol, r) in roles" :key="r">
              <option :value="rol.name">{{ rol.label }}</option>
            </template>
          </select>
        </div>
        <br />
        <button
          type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-4 rounded-xl transition duration-200"
        >
          Create
        </button>
      </form>
    </div>
  </AppLayout>
</template>
