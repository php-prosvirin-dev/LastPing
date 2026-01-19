<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                Welcome to LastPing
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 max-w-2xl">
                LastPing quietly watches for your check-ins and alerts your trusted contacts
                if something goes wrong. Take a moment to review your settings so the system
                can act exactly the way you intend — only when it truly matters.
            </p>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-16">
            <div x-data="checkInTimer({interval: {{ $intervalSeconds }},lastCheckIn: {{ $lastCheckIn }},})"
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
    </div>
</x-app-layout>

<script>
    function checkInTimer({ interval, lastCheckIn }) {
        return {
            interval,
            lastCheckIn,
            remaining: 0,
            timer: null,
            isLoading: false,
            start() {
                if (this.lastCheckIn === null) {
                    this.remaining = this.interval
                    return
                }
                this.updateRemaining()
                this.timer = setInterval(() => {
                    this.updateRemaining()
                }, 1000)
            },
            updateRemaining() {
                const now = Math.floor(Date.now() / 1000)
                const nextDue = this.lastCheckIn + this.interval
                this.remaining = Math.max(0, nextDue - now)
            },
            get formattedTime() {
                const mins = Math.floor(this.remaining / 60)
                const secs = this.remaining % 60
                return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`
            },
            async checkIn() {
                if (this.isLoading) return
                this.isLoading = true
                try {
                    const response = await fetch('/dashboard/check-in', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    })
                    const data = await response.json()
                    this.lastCheckIn = data.last_check_in_at
                    this.updateRemaining()
                } finally {
                    this.isLoading = false
                }
            },
        }
    }
</script>
