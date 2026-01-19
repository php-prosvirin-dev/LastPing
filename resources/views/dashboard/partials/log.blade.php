<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-12">
    <div class="rounded-lg border p-6 dark:border-gray-700">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Check-in Log
        </h2>

        <template x-if="logs.length === 0">
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                No check-ins yet.
            </p>
        </template>

        <ul class="mt-4 space-y-2 text-sm overflow-x-auto max-h-96">
            <template x-for="(log, index) in logs" :key="index">
                <li class="flex justify-between text-gray-700 dark:text-gray-300">
                    <span x-text="new Date(log.checked_in_at).toLocaleString()"></span>
                    <span class="text-gray-500" x-text="log.human"></span>
                </li>
            </template>
        </ul>
    </div>
</div>
