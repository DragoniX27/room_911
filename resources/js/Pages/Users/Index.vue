<script>
import { defineComponent } from "vue";

export default defineComponent({
  props: {
    users: {
      type: Object,
      required: false,
    },
  },
  data() {
    return {
      mod_users: this.users,
    };
  },
  mounted() {
    console.log(this.mod_users);
  },
});
</script>
<template>
    <AppLayout title="Users">
        <div class="py-12" id="user_index">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <div class="flex w-full text-xl p-3">
                        <div class="flex ml-auto">
                            <button class="rounded-full mx-2 bg-violet-500">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                            <button class="rounded-full mx-2 bg-lime-400">
                                <i class="fa-solid fa-file-arrow-up"></i>
                            </button>
                            <button class="rounded-full mx-2 bg-lime-600">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <table class="table-auto text-xl mx-auto text-center">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Department</th>
                                <th>Total Access</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, u) in mod_users" :key="u">
                                <td >{{ item.id }}</td>
                                <td>{{ item.name}}</td>
                                <td>{{ item.email}}</td>
                                <td>{{ item.phone}}</td>
                                <td>{{ item.department.name }}</td>
                                <td>{{ item.login_atemps_count }}</td>
                                <td>
                                    <div class="flex">
                                        <button class="rounded-full m-2 bg-sky-600">
                                            <i class="fa-solid fa-rotate"></i>
                                        </button>
                                        <button class="rounded-full m-2 bg-amber-500">
                                            <i class="fa-solid fa-lock"></i>
                                        </button>
                                        <button class="rounded-full m-2 bg-red-400">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </button>
                                        <button class="rounded-full m-2 bg-red-600">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<style scoped>
    #user_index{
        table{
            border-collapse: collapse;
            width: 100%;
            color: #111827;
            tr{
                border-color: 2px solid white;
                th,
                td{
                  border: 1px solid #ccc;
                  padding: 8px;
                }

                th{
                    background-color: #6875F5;
                    color: #ffffff;
                }
            }
        }
        button{
          padding: 3px 8px;
          transition: all 0.3s ease;
          &:hover{
              color:#ffffff;
              border-color: 1px solid #ffffff;
              cursor: pointer;
              transform: scale(1.05);
              box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
          }
        }
    }
</style>
