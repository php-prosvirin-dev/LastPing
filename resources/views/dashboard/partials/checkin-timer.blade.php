<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-16">
    <div x-data="checkInTimer({interval: {{ $interval_seconds }},lastCheckIn: {{ $last_check_in_at }},})"
         x-init="start()" class="rounded-lg border p-6 dark:border-gray-700 space-y-4 max-w-md">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Check-in Timer
        </h2>
        <div class="text-3xl font-mono text-gray-900 dark:text-gray-100"
             :class="remaining < 60 ? 'animate-pulse dark:text-red-600' : ''">
            <span x-text="formattedTime"></span>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Time remaining until your next required check-in.
        </p>
        <button type="button" @click="checkIn" :disabled="isLoading"
                :class="isLoading ? 'opacity-50 cursor-not-allowed' : ''"
                class="inline-flex items-center px-4 py-2 rounded-md
               bg-indigo-600 text-white hover:bg-indigo-500
               focus:outline-none focus:ring-2 focus:ring-indigo-500">
            Check in now
        </button>
    </div>
</div>
