<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    ticket: Object,
});

const statusForm = useForm({
    status: props.ticket.status,
});

const remarkForm = useForm({
    content: '',
});

function updateStatus() {
    statusForm.post(route('admin.tickets.updateStatus', props.ticket.ticket_id), {
        preserveScroll: true,
    });
}

function addRemark() {
    remarkForm.post(route('admin.tickets.internalRemarks', props.ticket.ticket_id), {
        onSuccess: () => remarkForm.reset(),
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Ticket Details" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Ticket Details
            </h2>
        </template>

        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Ticket Details -->
                        <div class="mb-6">
                            <h1 class="text-2xl font-bold text-gray-900">{{ ticket.subject }}</h1>
                            <div class="mt-2 space-y-1">
                                <p class="text-sm text-gray-700">
                                    <strong>User:</strong> {{ ticket.user.name }}
                                </p>
                                <p class="text-sm text-gray-700">
                                    <strong>Priority:</strong> {{ ticket.priority }}
                                </p>
                                <p class="text-sm text-gray-700">
                                    <strong>Status:</strong> {{ ticket.status }}
                                </p>
                                <p class="text-sm text-gray-700">
                                    <strong>Description:</strong> {{ ticket.description }}
                                </p>
                            </div>
                        </div>

                        <!-- Status Update Form -->
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800">Update Status</h2>
                            <form @submit.prevent="updateStatus" class="mt-4 space-y-4">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select
                                        id="status"
                                        v-model="statusForm.status"
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                        :class="{ 'border-red-500': statusForm.errors.status }"
                                    >
                                        <option value="Open">Open</option>
                                        <option value="In Progress">In Progress</option>
                                        <option value="Closed">Closed</option>
                                    </select>
                                    <p v-if="statusForm.errors.status" class="mt-1 text-sm text-red-600">{{ statusForm.errors.status }}</p>
                                </div>
                                <div>
                                    <button
                                        type="submit"
                                        :disabled="statusForm.processing"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
                                    >
                                        Update Status
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Internal Remarks Form -->
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800">Add Internal Remark</h2>
                            <form @submit.prevent="addRemark" class="mt-4 space-y-4">
                                <div>
                                    <label for="content" class="block text-sm font-medium text-gray-700">Remark</label>
                                    <textarea
                                        id="content"
                                        v-model="remarkForm.content"
                                        required
                                        rows="4"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                        :class="{ 'border-red-500': remarkForm.errors.content }"
                                    ></textarea>
                                    <p v-if="remarkForm.errors.content" class="mt-1 text-sm text-red-600">{{ remarkForm.errors.content }}</p>
                                </div>
                                <div>
                                    <button
                                        type="submit"
                                        :disabled="remarkForm.processing"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
                                    >
                                        Add Remark
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Comments Section -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Comments</h2>
                            <div v-if="ticket.comments.length" class="mt-4 space-y-4">
                                <div
                                    v-for="comment in ticket.comments"
                                    :key="comment.comment_id"
                                    class="p-4 rounded-md border border-gray-200"
                                    :class="{ 'bg-gray-50': !comment.is_internal, 'bg-yellow-50 border-yellow-200': comment.is_internal }"
                                >
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ comment.user.name }}
                                        <span class="text-xs text-gray-500">({{ new Date(comment.created_at).toLocaleString() }})</span>
                                        <span v-if="comment.is_internal" class="ml-2 inline-block px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded-full">Internal</span>
                                    </p>
                                    <p class="mt-1 text-sm text-gray-700">{{ comment.content }}</p>
                                </div>
                            </div>
                            <p v-else class="mt-2 text-sm text-gray-500">No comments yet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>