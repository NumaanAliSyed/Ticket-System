<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    ticket: Object,
});

const form = useForm({
    content: '',
});

function submitComment() {
    form.post(route('tickets.comments.store', props.ticket.ticket_id), {
        onSuccess: () => form.reset(),
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
                        <div class="mb-6">
                            <h1 class="text-2xl font-bold text-gray-900">{{ ticket.subject }}</h1>
                            <div class="mt-2 space-y-1">
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

                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800">Comments</h2>
                            <div v-if="ticket.comments.length" class="mt-4 space-y-4">
                                <div
                                    v-for="comment in ticket.comments"
                                    :key="comment.comment_id"
                                    class="p-4 bg-gray-50 rounded-md border border-gray-200"
                                >
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ comment.user.name }}
                                        <span class="text-xs text-gray-500">({{ new Date(comment.created_at).toLocaleString() }})</span>
                                    </p>
                                    <p class="mt-1 text-sm text-gray-700">{{ comment.content }}</p>
                                </div>
                            </div>
                            <p v-else class="mt-2 text-sm text-gray-500">No comments yet.</p>
                        </div>

                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Add Comment</h2>
                            <form @submit.prevent="submitComment" class="mt-4 space-y-4">
                                <div>
                                    <label for="content" class="block text-sm font-medium text-gray-700">Comment</label>
                                    <textarea
                                        id="content"
                                        v-model="form.content"
                                        required
                                        rows="4"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                        :class="{ 'border-red-500': form.errors.content }"
                                    ></textarea>
                                    <p v-if="form.errors.content" class="mt-1 text-sm text-red-600">{{ form.errors.content }}</p>
                                </div>
                                <div>
                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
                                    >
                                        Add Comment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>