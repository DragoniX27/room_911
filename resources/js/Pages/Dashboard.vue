<script setup>

</script>

<template>
    <AppLayout title="Dashboard">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <div class="flex grid grid-cols-1 md:grid-cols-3 ">
                        <div class="w-25 p-2 flex-auto text-center bg-white rounded-md" @click="$inertia.visit(route('users.index'))">
                            <i class="fa-solid fa-user text-6xl"></i>
                            <p class="text-4xl"><strong>Users</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
