<script setup>
import { ref, onMounted, watchEffect, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { formatDate } from '@/Utils/formatDate';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';

const breadcrumbs = [
    { text: 'Dashboard', link: '/dashboard' },
    { text: 'Tickets', link: '/tickets' },
    { text: 'Show', link: '' },
];

const props = defineProps({
    ticket: Array
});

const priorities = {
    low: 'Low',
    medium: 'Medium',
    high: 'High',
};

const statuses = {
    open: 'Open',
    in_progress: 'In Progress',
    closed: 'Closed',
};
</script>

<template>
    <AuthenticatedLayout>
        <!-- Page Breadcrumbs -->
        <Breadcrumb :breadcrumbs="breadcrumbs"> </Breadcrumb>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-5">
            <div class="flex flex-col-reverse sm:flex-row justify-between items-center py-6">
                <h1 class="text-2xl font-semibold text-gray-600">Ticket Details</h1>
                <!-- Create Ticket button -->
                <Link :href="route('tickets.index')" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Back
                </Link>
            </div>
            <div class="sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-700">
                    <tbody class="divide-y divide-gray-700">
                    <tr>
                        <td class="px-4 py-4 text-sm">ID</td>
                        <td class="px-4 py-4 text-sm text-gray-400">{{ ticket.id }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-4 text-sm">Title</td>
                        <td class="px-4 py-4 text-sm text-gray-400">{{ ticket.title }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-4 text-sm">Content</td>
                        <td class="px-4 py-4 text-sm text-gray-400">{{ ticket.description }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-4 text-sm">Username</td>
                        <td class="px-4 py-4 text-sm text-gray-400">{{ ticket.user.name }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-4 text-sm">Email</td>
                        <td class="px-4 py-4 text-sm text-gray-400">{{ ticket.user.email }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-4 text-sm">Priority</td>
                        <td class="px-4 py-4 text-sm text-gray-400">{{ priorities[ticket.priority] }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-4 text-sm">Status</td>
                        <td class="px-4 py-4 text-sm text-gray-400">{{ statuses[ticket.status] }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-4 text-sm">Submitted</td>
                        <td class="px-4 py-4 text-sm text-gray-400">{{ formatDate(ticket.created_at, 'MM/DD/YYYY') }}</td>
                    </tr>
                    </tbody>
                </table>
                <Pagination
                    :total-items="totalItems"
                    :current-page.sync="currentPage"
                    :per-page="perPage"
                    @update:currentPage="handlePageChange"
                />
            </div>
        </div>
    </AuthenticatedLayout>

</template>

