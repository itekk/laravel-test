<script setup>
import { computed, defineEmits } from 'vue';

const props = defineProps({
    totalItems: Number,
    currentPage: Number,
    perPage: Number
});

const emits = defineEmits(['update:currentPage']);

const totalPages = computed(() => {
    return Math.ceil(props.totalItems / props.perPage);
});

function goToPage(newPage) {
    if (newPage >= 1 && newPage <= props.totalItems && newPage !== props.currentPage) {
        emits('update:currentPage', newPage);
    }
}
</script>
<template>
    <div class="flex justify-between items-center mt-4">
        <!-- Previous control -->
        <button
            :disabled="currentPage === 1"
            @click="goToPage(currentPage - 1)"
            class="bg-gray-800 py-2 px-4 text-sm font-medium text-gray-300 hover:bg-gray-700"
            :class="{ 'opacity-75': currentPage === 1 }"
        >
            Previous
        </button>

        <!-- Page numbers -->
        <ul class="flex space-x-1">
            <li
                v-for="page in totalPages"
                :key="page"
                @click="goToPage(page)" 
                class="cursor-pointer py-2 px-4 leading-tight bg-gray-800 border border-gray-600 text-gray-400 hover:bg-gray-700 hover:text-white"
                :class="(page === currentPage) ? 'bg-blue-600 text-white' : 'opacity-75'"
            >
                {{ page }}
            </li>
        </ul>

        <!-- Next control -->
        <button
            :disabled="currentPage === totalPages"
            @click="goToPage(currentPage + 1)"
            class="bg-gray-800 py-2 px-4 text-sm font-medium text-gray-300 hover:bg-gray-700"
            :class="{ 'opacity-75': currentPage === totalPages }"
        >
            Next
        </button>
    </div>
</template>
