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
    user:{
        type: Object,
    }
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
      id: 0,
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
    formatDate(dateStr){
        const date = new Date(dateStr)
        return date.toLocaleString()
    },
    submit() {
      this.$showLoading();
      this.$inertia.post(
        route("users.update"),
        { form: this.form, role: this.role, 'id' : this.id },
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
    console.log(this.user);
    this.id = this.user.id,
    this.form.name = this.user.name
    this.form.email = this.user.email
    this.form.phone = this.user.phone
    this.form.department_id = this.user.department_id
    this.form.status = this.user.status
    this.role = this.user.roles[0].name
  },
});
</script>
<template>
  <AppLayout title="Update User">
    <div class="bg-gray-100 mt-5 flex items-center justify-center">
      <form
        class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-lg space-y-3"
        @submit.prevent="submit"
      >
        <h2 class="text-2xl font-bold text-gray-800 text-center">Update User</h2>
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
            >Password
        </label>
        <small>*just enter the password when you want to change it</small>
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
          Update
        </button>
      </form>
      <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-lg space-y-3 ml-2">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Login attempts</h2>
        <div class="bg-white rounded-2xl shadow-md p-4 max-h-96 overflow-y-auto space-y-3 border border-gray-200">
            <template v-if="user.login_attempts.length > 0">
                <div
                  v-for="(attempt, index) in user.login_attempts"
                  :key="index"
                  class="p-3 rounded-lg border-l-4"
                  :class="attempt.status == 'success' ? 'border-green-500 bg-green-50' : 'border-red-500 bg-red-50'"
                >
                  <div class="flex justify-between items-center text-sm text-gray-700">
                    <span><strong>IP:</strong> {{ attempt.ip_address }}</span>
                    <span><strong>Date:</strong> {{ formatDate(attempt.created_at) }}</span>
                  </div>
                  <div class="mt-1 text-sm">
                    Status:
                    <span :class="attempt.status == 'success' ? 'text-green-600' : 'text-red-600'">
                      {{ attempt.status }}
                    </span>
                  </div>
                  <div class="mt-1 text-sm">
                    Device:
                    <span>
                      {{ attempt.user_agent }}
                    </span>
                  </div>
                </div>
            </template>
            <p v-else>there are no records...</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
