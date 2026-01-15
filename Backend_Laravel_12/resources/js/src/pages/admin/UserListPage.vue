<script lang="ts" setup>
import { ref, onMounted, computed,watch } from "vue";
import { getUsers } from "@/api/user";
import { DataTablePayload } from "@/types/global";
import { UserRolePermission } from "@/types/user";
import Error from "@/components/Error.vue";
import BaseInput from "@/components/BaseInput.vue";
import BaseBtn from "@/components/BaseBtn.vue";

const users = ref<UserRolePermission[]>([]);
const page = ref(1);
const perPage = ref(30);
const recordTotal = ref(0);
const searchValue = ref("");
const sortColumn = ref("name");
const sortDirection = ref<"asc" | "desc">("asc");

const currentUserId = Number((document.getElementById("app") as HTMLElement).dataset.userId) ?? 1;


const totalPages = computed(() => {
    return Math.ceil(recordTotal.value / perPage.value);
});

const emits = defineEmits<{
    (e: "edit-user", value: UserRolePermission): void;
}>();

async function loadUsers() {
    const payload: DataTablePayload = {
        draw:1,
        start:(page.value - 1) * perPage.value,
        length: perPage.value,
        search: { value: searchValue.value },
        status:undefined,
        order: [
            {
                column: sortColumn.value,
                dir: sortDirection.value
            }
        ],
        columns: [
            { data: "name" },
            { data: "email" },
            { data: "roles" }
        ]
    };
    try {
        const data:DataTablePayResponse = await getUsers(payload);
        console.log(data)
        users.value = data.data;
        recordTotal.value = data.recordsTotal;
    } catch (error) {
        console.error("Error loading users:", error);
    }
}

onMounted(() => {
    loadUsers();
});

watch(searchValue, () =>{
    page.value = 1;
    loadUsers();
});

const sort = (column: string) => {
    if (sortColumn.value === column) {
        // Toggle sort direction
        sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
    } else {
        sortColumn.value = column;
        sortDirection.value = "asc";
    }
    loadUsers();
}
</script>
<template>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4 class="font-weight-semibold">
                    <font-awesome-icon icon="fa-solid fa-person-walking-arrow-loop-left" flip="horizontal" size="sm" />
                    Users Record
                </h4>
                <a href="#" class="header-elements-toggle text-default d-md-none">
                    <font-awesome-icon icon="fa-thin fa-person-walking-arrow-loop-left" flip="horizontal" />
                </a>
            </div>
        </div>
    </div>
    <!-- /page header -->


    <!-- Content area -->
    <div class="content pt-0">

        <!-- Basic datatable -->
        <div class="card">
            <div class="card-header header-elements-inline">
                <!-- FILTERS -->
                <div class="flex gap-3 mb-4">
                    <input
                        v-model="searchValue"
                        @input="loadUsers"
                        class="form-control w-10"
                        placeholder="Search users..."
                    />

                    <!-- <select v-model="status" @change="loadUsers" class="form-select w-48">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="disabled">Disabled</option>
                    </select> -->
                </div>
                <div class="header-elements">
                    <div class="ml-3">
                        <a href="#" class="btn bg-success" id="addUser"><i class="icon-add mr-2"></i> Add User</a>
                    </div>
                </div>
            </div>

            <table class="table table-striped w-full">
                <thead>
                    <tr>
                        <th @click="sort('name')">User</th>
                        <th @click="sort('email')">Email</th>
                        <th @click="sort('roles.name')">Roles</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>
                            <span v-for="role in user.roles" :key="role" class="badge bg-info me-1">
                                {{ role }}
                            </span>
                        </td>
                        <td>
                            <BaseBtn type="button" label="Edit" class="btn btn-sm btn-light" @click="$emit('edit-user', user)" />
                            <BaseBtn type="button"  label="Delete" class="btn btn-sm btn-danger" />

                        </td>
                        <!-- <td>
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <font-awesome-icon icon="fa-solid fa-list" />
                                </a>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <a
                                        href="javascript:void(0);"
                                        class="dropdown-item"
                                        @click="$emit('edit-user', user)"
                                    >
                                        <font-awesome-icon icon="fa-regular fa-pen-to-square" class="mr-1"/>Edit
                                    </a>

                                    <a
                                        v-if="user.id !== currentUserId"
                                        href="javascript:void(0);"
                                        class="dropdown-item"
                                        @click="$emit('delete-user', user)"
                                    >
                                        <i class="icon-trash mr-1"></i> Delete
                                    </a>

                                    <span
                                        v-else
                                        class="dropdown-item text-muted"
                                        style="cursor: not-allowed;"
                                    >
                                        <i class="icon-lock mr-1"></i> Cannot delete yourself
                                    </span>

                                    <div class="dropdown-divider"></div>

                                    <a
                                        href="javascript:void(0);"
                                        class="dropdown-item"
                                        @click="$emit('resend-email', user)"
                                    >
                                        <i class="icon-mailbox mr-1"></i> Resend Email
                                    </a>
                                </div>
                            </div>
                        </td> -->

                        <!-- <td>
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="javascript:void(0);" class="dropdown-item editUser" @click="$emit('edit-user', user)"><i class="icon-pencil mr-1"></i>Edit</a>'
                                        <a  v-if="user.id !== currentUserId" href="javascript:void(0);" class="dropdown-item deleteUser" @click="$emit('delete-user', user)"><i class="icon-trash mr-1"></i>Delete</a>

                                        <div class="dropdown-divider"></div>
                                        <a href="javascript:void(0);" class="dropdown-item resendEmail" @click="$emit('resend-email', user)"><i class="icon-mailbox mr-1"></i>Resend Email</a>
                                    </div>
                                </div>
                            </div>
                        </td> -->
                    </tr>
                </tbody>
            </table>

            <nav class="mt-4 flex gap-2">
                <button
                    class="btn btn-secondary"
                    :disabled="page === 1"
                    @click="page>1 && (page--, loadUsers())"
                >
                    Prev
                </button>

                <span class="px-2 py-1">
                    Page {{ page }} of {{ totalPages }}
                </span>

                <button
                    class="btn btn-secondary"
                    :disabled="page === totalPages"
                    @click="page < totalPages && (page++,loadUsers())"
                >
                    Next
                </button>
                </nav>
        </div>
    </div>
    <!-- /basic datatable -->
</template>
<style lang="">

</style>
