<script setup>
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import { priorities } from '@/Utils/priorities';
import { statuses } from '@/Utils/statuses';

const breadcrumbs = [
    { text: 'Dashboard', link: '/dashboard' },
    { text: 'Tickets', link: '/tickets' },
    { text: 'Create', link: '' },
];

const form = useForm({
    title: '',
    description: '',
    priority: 'low',
    status: 'open',
});

// Suggestion: Add error handling to catch potential issues during form submission.
function submit() {
    form.post('/tickets', {
        // Suggestion: Consider adding a success message to notify the user that the ticket has been created successfully.
        onSuccess: () => form.reset('title', 'description', 'priority', 'status'),
    });
}
</script>

<template>
    <AuthenticatedLayout>

        <!-- Page Breadcrumbs -->
        <Breadcrumb :breadcrumbs="breadcrumbs"> </Breadcrumb>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form @submit.prevent="submit">
                    <!-- Suggestion: Implement CSRF token protection in forms to safeguard against CSRF vulnerabilities.-->
                    <div class="shadow overflow-hidden sm:rounded-md bg-gray-800 p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6">
                                <label for="title" class="block text-sm font-medium text-gray-300">Title</label>
                                <input type="text" name="title" id="title" v-model="form.title"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-700 text-white">
                                <p v-if="form.errors.title" class="text-red-500 text-xs italic">{{ form.errors.title }}</p>
                            </div>

                            <div class="col-span-6">
                                <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                                <textarea id="description" name="description" v-model="form.description"
                                          rows="3"
                                          class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md bg-gray-700 text-white"></textarea>
                                <p v-if="form.errors.description" class="text-red-500 text-xs italic">{{ form.errors.description }}</p>
                            </div>

                            <div class="col-span-6">
                                <label for="priority" class="block text-sm font-medium text-gray-300">Priority</label>
                                <select id="priority" name="priority" v-model="form.priority"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-gray-700 text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option v-for="(label, priority) in priorities" :value="priority">{{ label }}</option>
                                </select>
                                <p v-if="form.errors.priority" class="text-red-500 text-xs italic">{{ form.errors.priority }}</p>
                            </div>

                            <div class="col-span-6">
                                <label for="status" class="block text-sm font-medium text-gray-300">Status</label>
                                <select id="status" name="status" v-model="form.status"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-gray-700 text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option v-for="(label, status) in statuses" :value="status">{{ label }}</option>
                                </select>
                                <p v-if="form.errors.status" class="text-red-500 text-xs italic">{{ form.errors.status }}</p>
                            </div>
                        </div>

                        <div class="py-6 text-right">
                            <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Ticket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
