<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    tickets: Array,
    users: Array,
    filters: Object,
});

const form = useForm({
    status: props.filters.status || '',
    priority: props.filters.priority || '',
    date: props.filters.date || '',
    user_id: props.filters.user_id || '',
});

function applyFilters() {
    form.get(route('admin.tickets.index'), {
        preserveState: true,
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="All Tickets" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                All Tickets
            </h2>
        </template>

        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="applyFilters" class="mb-6 flex flex-col sm:flex-row sm:items-end gap-4">
                            <div class="flex-1">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                    :class="{ 'border-red-500': form.errors.status }"
                                >
                                    <option value="">All Statuses</option>
                                    <option value="Open">Open</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Closed">Closed</option>
                                </select>
                                <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">{{ form.errors.status }}</p>
                            </div>
                            <div class="flex-1">
                                <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                                <select
                                    id="priority"
                                    v-model="form.priority"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                    :class="{ 'border-red-500': form.errors.priority }"
                                >
                                    <option value="">All Priorities</option>
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                </select>
                                <p v-if="form.errors.priority" class="mt-1 text-sm text-red-600">{{ form.errors.priority }}</p>
                            </div>
                            <div class="flex-1">
                                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                <input
                                    id="date"
                                    v-model="form.date"
                                    type="date"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                    :class="{ 'border-red-500': form.errors.date }"
                                />
                                <p v-if="form.errors.date" class="mt-1 text-sm text-red-600">{{ form.errors.date }}</p>
                            </div>
                            <div class="flex-1">
                                <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                                <select
                                    id="user_id"
                                    v-model="form.user_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                    :class="{ 'border-red-500': form.errors.user_id }"
                                >
                                    <option value="">All Users</option>
                                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                                </select>
                                <p v-if="form.errors.user_id" class="mt-1 text-sm text-red-600">{{ form.errors.user_id }}</p>
                            </div>
                            <div>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
                                >
                                    Filter
                                </button>
                            </div>
                        </form>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="ticket in tickets" :key="ticket.ticket_id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ticket.subject }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ticket.user.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ticket.priority }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ticket.status }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <Link
                                                :href="route('admin.tickets.show', ticket.ticket_id)"
                                                class="text-blue-600 hover:text-blue-800 font-medium"
                                            >
                                                View
                                            </Link>
                                        </td>
                                    </tr>
                                    <tr v-if="!tickets.length" class="hover:bg-gray-50">
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No tickets found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>