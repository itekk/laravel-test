<script setup>
import { ref, watch, computed } from 'vue';
import { priorities } from '@/Utils/priorities';
import { statuses } from '@/Utils/statuses';
import Datepicker from 'vue3-datepicker';

const props = defineProps({
  initialFilters: {
    type: Object,
    default: () => ({
      title: '',
      content: '',
      username: '',
      email: '',
      dateRange: [null, null],
      priority: '',
      status: '',
    }),
  },
});

const emit = defineEmits(['apply-filters']);

const selectedFilter = ref('');
const searchTerm = ref('');
const dateRange = ref([null, null]);
const priority = ref('');
const status = ref('');

watch(
  () => props.initialFilters,
  ({ title, content, username, email, dateRange: range, priority: prio, status: stat }) => {
    searchTerm.value = title || content || username || email;
    dateRange.value = range.map(date => date ? new Date(date) : null);
    priority.value = prio || '';
    status.value = stat || '';
  },
  { immediate: true }
);

watch(selectedFilter, (newFilter) => {
  if (['title', 'content', 'username', 'email'].includes(newFilter)) {
    dateRange.value = [null, null];
    priority.value = '';
    status.value = '';
  } else if (newFilter === 'date_range') {
    searchTerm.value = '';
    priority.value = '';
    status.value = '';
  } else if (newFilter === 'priority') {
    searchTerm.value = '';
    dateRange.value = [null, null];
    status.value = '';
  } else if (newFilter === 'status') {
    searchTerm.value = '';
    dateRange.value = [null, null];
    priority.value = '';
  } else {
    searchTerm.value = '';
    dateRange.value = [null, null];
    priority.value = '';
    status.value = '';
  }
});

const clearFilters = () => {
  selectedFilter.value = '';
  searchTerm.value = '';
  dateRange.value = [null, null];
  priority.value = '';
  status.value = '';
  
  emit('apply-filters', {
    title: '',
    content: '',
    username: '',
    email: '',
    dateRange: [null, null],
    priority: '',
    status: '',
  });
};

const filterOptions = computed(() => ({
  title: selectedFilter.value === 'title' ? searchTerm.value : '',
  content: selectedFilter.value === 'content' ? searchTerm.value : '',
  username: selectedFilter.value === 'username' ? searchTerm.value : '',
  email: selectedFilter.value === 'email' ? searchTerm.value : '',
  dateRange: selectedFilter.value === 'date_range' ? dateRange.value : [null, null],
  priority: selectedFilter.value === 'priority' ? priority.value : '',
  status: selectedFilter.value === 'status' ? status.value : '',
}));

const applyFilters = () => {
  emit('apply-filters', filterOptions.value);
};

const hasActiveFilters = computed(() => (
  searchTerm.value ||
  dateRange.value.some(date => date) ||
  priority.value ||
  status.value
));
</script>

<template>
  <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
    <div v-if="hasActiveFilters">
      <button @click="clearFilters"
        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 ml-2"
      >
        Clear Filters
      </button>
    </div>

    <!-- Input fields based on the selected filter -->
    <div v-if="['title', 'content', 'username', 'email'].includes(selectedFilter)">
      <input type="text" v-model="searchTerm" placeholder="Search Term"
        class="w-full sm:w-auto rounded-md px-2 py-1.5 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600"
      />
    </div>

    <!-- Date Range Picker -->
    <div v-if="selectedFilter === 'date_range'" class="flex items-center w-full sm:w-auto">
      <span class="mr-2">Date Range:</span>
      <Datepicker v-model="dateRange[0]" inputFormat="MM/dd/yyyy" placeholder="From" typeable 
        class="w-full sm:w-auto rounded-md px-2 py-1.5 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 mr-2" 
      />
      <span class="mx-2">-</span>
      <Datepicker v-model="dateRange[1]" inputFormat="MM/dd/yyyy" placeholder="To" typeable 
        class="w-full sm:w-auto rounded-md px-2 py-1.5 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" 
      />
    </div>

    <!-- Priority and Status Dropdowns -->
    <select v-if="selectedFilter === 'priority'" v-model="priority"
      class="w-full sm:w-auto rounded-md py-1 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600"
    >
      <option value="">Select Priority</option>
      <option v-for="(label, priority) in priorities" :value="priority">{{ label }}</option>
    </select>

    <select v-if="selectedFilter === 'status'" v-model="status"
      class="w-full sm:w-auto rounded-md py-1 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600"
    >
      <option value="">Select Status</option>
      <option v-for="(label, status) in statuses" :value="status">{{ label }}</option>
    </select>

    <!-- Filter Selection Dropdown -->
    <select v-model="selectedFilter"
      class="rounded-md py-1 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 w-full sm:w-auto"
    >
      <option value="">Select Filter</option>
      <option value="title">Title</option>
      <option value="content">Content</option>
      <option value="username">Username</option>
      <option value="email">Email</option>
      <option value="date_range">Date Range</option>
      <option value="priority">Priority</option>
      <option value="status">Status</option>
    </select>

    <!-- Apply Filters Button -->
    <button @click="applyFilters"
      class="w-full sm:w-auto py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Apply Filters
    </button>
  </div>
</template>
