<script setup>
import Pagination from '@/Components/Pagination.vue';
import { ref, onMounted, watchEffect, computed } from 'vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';
import { formatDate } from '@/Utils/formatDate';
import { priorities } from '@/Utils/priorities';
import { statuses } from '@/Utils/statuses';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import FilterOptions from '@/Components/FilterOptions.vue';

const breadcrumbs = [
    { text: 'Dashboard', link: '/dashboard' },
    { text: 'Tickets', link: '' },
];

const props = defineProps({
    tickets: Array,
    totalItems: Number, // Changed from Integer to Number
});

const currentPage = ref(1);
const perPage = ref(10);
const filteredTickets = ref(props.tickets);
const totalItems = ref(props.totalItems);

// Define initial filters
const initialFilters = {
    title: '',
    content: '',
    username: '',
    email: '',
    dateRange: ['', ''],
    priority: '',
    status: ''
};

// Define filters object
const filters = ref({ ...initialFilters });

// Function to handle the apply-filters event from FilterOptions component
const handleApplyFilters = async (newFilters) => {
    filters.value = { ...newFilters };
    currentPage.value = 1;
    await fetchFilteredTickets();
};

// Function to apply filters
const fetchFilteredTickets = async () => {
    const apiUrl = '/tickets/filter';

    // Add pagination parameters
    const queryParams = {
        page: currentPage.value,
        per_page: perPage.value,
        ...filters.value,
    };

    // Send the API request with the filters and pagination parameters
    try {
        const response = await axios.post(apiUrl, queryParams);
        const data = response.data;
        filteredTickets.value = data.tickets || []; // Update the filtered tickets
        totalItems.value = data.totalItems || 0; // Update total pages for pagination
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

// Function to handle page change event from Pagination component
const handlePageChange = async (newPage) => {
    currentPage.value = newPage;
    // Fetch data based on the new page
    await fetchFilteredTickets(initialFilters);
};

// Use watchEffect to keep track of changes to props and update state variables
watchEffect(() => {
    filteredTickets.value = props.tickets;
    totalItems.value = props.totalItems;
});

const paginatedTickets = computed(() => {
    return filteredTickets.value;
})

// Fetch initial data on component mount
onMounted(() => {
    fetchFilteredTickets(initialFilters);
});
</script>

<template>
    <AuthenticatedLayout>
        <!-- Page Breadcrumbs -->
        <Breadcrumb :breadcrumbs="breadcrumbs"> </Breadcrumb>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col-reverse sm:flex-row justify-between items-center py-6">
                <h1 class="text-2xl font-semibold text-gray-600">Tickets</h1>
                <div class="flex flex-col sm:flex-row space-x-0 space-y-2 sm:space-y-0 sm:space-x-3">
                    <!-- Apply Filters button -->
                    <FilterOptions
                        @apply-filters="handleApplyFilters"
                        :initial-filters="initialFilters"
                    />

                    <!-- Create Ticket button -->
                    <Link :href="route('tickets.create')" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create Ticket
                    </Link>
                </div>
            </div>
            <div class="overflow-x-auto shadow  sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Submitted
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Title
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            User
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Priority
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            &nbsp;
                        </th>

                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                    <tr v-for="ticket in paginatedTickets" :key="ticket.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ ticket.id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ formatDate(ticket.created_at, 'MM/DD/YYYY') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ ticket.title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ ticket.user.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ priorities[ticket.priority] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ statuses[ticket.status] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                            <Link :href="route('tickets.show', [ticket.id])">
                                View
                            </Link>
                        </td>
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

