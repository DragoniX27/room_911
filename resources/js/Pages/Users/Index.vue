<script>
import DialogModal from '@/Components/DialogModal.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import { toast } from 'vue3-toastify';
import { defineComponent,ref  } from "vue";

export default defineComponent({
    components:{
        DialogModal,
        PrimaryButton,
        SecondaryButton
    },
  props: {
    users: {
      type: Object,
      required: false,
    },
  },
  data() {
    return {
      mod_users: this.users,
      showModal: ref(false),
      form: this.$inertia.form({
        name: "",
        email: "",
        phone: "",
      }),
    };
  },
  methods:{
    closeModal(){
        this.showModal = false
    },
    searchUser(){
        this.showModal = false
        this.$showLoading();
        axios.post(route('users.search'), { 'form': this.form }).then(res => {
            this.mod_users = res.data.users
            this.$hideLoading();
        });
    },
    //Function to put the phone format in a field
    formatAsPhoneNumber() {
      let num = this.form.phone.replace(/\D/g, "");
      if (num.length > 10) num = num.slice(0, 10);

      if (num.length >= 6) {
        this.form.phone = `(${num.slice(0, 3)}) ${num.slice(3, 6)}-${num.slice(6)}`;
      } else if (num.length >= 3) {
        this.form.phone = `(${num.slice(0, 3)}) ${num.slice(3)}`;
      } else if (num.length > 0) {
        this.form.phone = `(${num}`
      }
    },
    //Function to block or unblock a user
    blockUser(id){
        axios.post(route('users.block', id)).then(res => {
            if(res.data.status){
                let index = this.mod_users.findIndex(item => item.id === id)
                this.mod_users[index].status = res.data.Ustatus
                toast(('User '+((res.data.Ustatus == 'active')? 'block':'unblock')), { type: 'success', "autoClose": 3000})
            }else{
                toast('Something went wrong, please try again.', { type: 'error', "autoClose": 3000})
            }
        });
    },
    //Function to delete a user
    deleteUser(id){
        axios.post(route('users.destroy', id)).then(res => {
            if(res.data.status){
                let index = this.mod_users.findIndex(item => item.id === id)
                this.mod_users.splice(index, 1);
                toast(('Deleted user'), { type: 'success', "autoClose": 3000})
            }else{
                toast('Something went wrong, please try again.', { type: 'error', "autoClose": 3000})
            }
        });
    },
    usersImport(event){
        this.$showLoading()
        let file = event.target.files[0]
        let formData = new FormData()
        formData.append('file', file)

        axios.post(route('users.import'), formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(res =>{
            if(res.data.status){
                this.$refs.fileInput.value = null
                this.mod_users = res.data.users
                this.$hideLoading()
                toast('Amount made successfully', { type: 'success', "autoClose": 3000})
            }else{
                this.$refs.fileInput.value = null
                this.$hideLoading()
                if(Array.isArray(res.data.errors)){
                    for(let i = 0; i < res.data.errors.length; i++){
                        toast('The Row #'+res.data.errors[i]['row']+' '+res.data.errors[i]['errors'], { type: 'error', "autoClose": 3000})
                    }
                }else{
                    toast(res.data.errors, { type: 'error', "autoClose": 3000})
                }
            }
        });
    }
  },
  mounted() {
    this.$hideLoading()
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
                            <input type="file" ref="fileInput" @change="usersImport" style="display: none"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                            <button class="rounded-full mx-2 bg-violet-500"  @click="showModal = true">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                            <button class="rounded-full mx-2 bg-lime-400" @click="$refs.fileInput.click()">
                                <i class="fa-solid fa-file-arrow-up"></i>
                            </button>
                            <button class="rounded-full mx-2 bg-lime-600" @click="$inertia.visit(route('users.create'))">
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
                                <td>{{ item.login_attempts_count }}</td>
                                <td>
                                    <div class="flex">
                                        <button class="rounded-full m-2 bg-sky-600" @click="$inertia.visit(route('users.edit', item.id))">
                                            <i class="fa-solid fa-rotate"></i>
                                        </button>
                                        <button class="rounded-full m-2 bg-amber-500" @click="blockUser(item.id)">
                                            <i v-show="(item.status == 'active')" class="fa-solid fa-lock"></i>
                                            <i v-show="(item.status == 'inactive')" class="fa-solid fa-lock-open"></i>
                                        </button>
                                        <a :href="route('users.pdf', { user: item.id })" target="_blank" class="rounded-full m-2 bg-red-400">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </a>
                                        <button class="rounded-full m-2 bg-red-600" @click="deleteUser(item.id)">
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
        <DialogModal :show="showModal" @close="closeModal">
            <template #title>
                Search user
            </template>
            <template #content>
                <form @submit.prevent="searchUser" class="space-y-4">
                  <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">name</label>
                    <input
                      id="name"
                      type="text"
                      v-model="form.name"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"
                    />
                  </div>
                  <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">email</label>
                    <input
                      id="email"
                      type="email"
                      v-model="form.email"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"
                    />
                  </div>
                  <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">phone</label>
                    <input
                      id="phone"
                      type="text"
                      v-model="form.phone"
                      @input="formatAsPhoneNumber"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"
                    />
                  </div>
                </form>
            </template>
            <template #footer>
                <SecondaryButton @click="closeModal">
                  Close
                </SecondaryButton>

                <PrimaryButton @click="searchUser" class="ml-2">
                  Search
                </PrimaryButton>
            </template>
        </DialogModal>
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
        button, a{
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
