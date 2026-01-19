window.checkInTimer = function ({ interval, lastCheckIn, logs }) {
    return {
        interval,
        lastCheckIn,
        logs: logs ?? [],
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
                this.logs = data.check_ins
                this.updateRemaining()
            } finally {
                this.isLoading = false
            }
        }
    }
}
