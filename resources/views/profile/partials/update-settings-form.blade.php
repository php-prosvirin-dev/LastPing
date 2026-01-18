<section class="space-y-10">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile & Monitoring Settings') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Manage your identity and LastPing behavior.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-10">
        @csrf
        @method('patch')

        @if ($errors->any())
            <div class="rounded-md bg-red-50 dark:bg-red-900/30 p-4">
                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                    {{ __('There were some problems with your input.') }}
                </h3>

                <ul class="mt-2 text-sm text-red-700 dark:text-red-300 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-6">
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input
                    id="name"
                    name="name"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('name', $user->name)"
                    required
                    autofocus
                />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input
                    id="email"
                    name="email"
                    type="email"
                    class="mt-1 block w-full"
                    :value="old('email', $user->email)"
                    required
                />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <h3 class="font-medium text-gray-900 dark:text-gray-100">
                {{ __('Check-in Rules') }}
            </h3>

            <div>
                <x-input-label :value="__('Check-in Interval (minutes)')" />
                <x-text-input
                    type="number"
                    min="1"
                    name="settings[check_in][interval_minutes]"
                    class="mt-1 block w-full"
                    :value="old('settings.check_in.interval_minutes', $user->settings['check_in']['interval_minutes'])"
                    required
                />
            </div>

            <div>
                <x-input-label :value="__('Grace Period (minutes)')" />
                <x-text-input
                    type="number"
                    min="0"
                    name="settings[check_in][grace_period_minutes]"
                    class="mt-1 block w-full"
                    :value="old('settings.check_in.grace_period_minutes', $user->settings['check_in']['grace_period_minutes'])"
                    required
                />
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="font-medium text-gray-900 dark:text-gray-100">
                {{ __('Emergency Notifications') }}
            </h3>

            <div class="flex items-center gap-2">
                <input
                    type="checkbox"
                    name="settings[notifications][enabled]"
                    value="1"
                    @checked($user->settings['notifications']['enabled'])
                >
                <span class="text-sm text-gray-700 dark:text-gray-300">
                    {{ __('Enable notifications') }}
                </span>
            </div>

            <div
                x-data="{
                    siblings: @js(old('settings.notifications.siblings', $user->settings['notifications']['siblings'] ?? [])),
                    addSibling() { this.siblings.push({ name: '', email: '' }) },
                    removeSibling(index) { this.siblings.splice(index, 1) }
                }"
                class="space-y-4"
            >
                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Emergency Contacts') }}
                </h4>

                <p x-show="siblings.length === 0" class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('No emergency contacts added.') }}
                </p>

                <template x-for="(sibling, index) in siblings" :key="index">
                    <div class="relative grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border rounded-md dark:border-gray-700">
                        <button
                            type="button"
                            @click="removeSibling(index)"
                            class="absolute top-2 right-2 p-1 rounded hover:bg-red-100 dark:hover:bg-red-900/30"
                        >
                            <x-heroicon-o-trash class="w-5 h-5 text-red-600 dark:text-red-400" />
                        </button>

                        <x-text-input
                            x-bind:name="`settings[notifications][siblings][${index}][name]`"
                            x-model="sibling.name"
                            placeholder="{{ __('Name') }}"
                            class="w-full"
                        />

                        <x-text-input
                            type="email"
                            x-bind:name="`settings[notifications][siblings][${index}][email]`"
                            x-model="sibling.email"
                            placeholder="{{ __('Email') }}"
                            class="w-full"
                        />
                    </div>
                </template>

                <button
                    type="button"
                    @click="addSibling"
                    class="inline-flex items-center text-sm text-indigo-600 dark:text-indigo-400 hover:underline"
                >
                    + {{ __('Add emergency contact') }}
                </button>
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="font-medium text-gray-900 dark:text-gray-100">
                {{ __('Data Handling') }}
            </h3>

            <div>
                <x-input-label :value="__('Purge Method')" />
                <select
                    name="settings[data_handling][purge_method]"
                    class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                >
                    <option value="archive" @selected($user->settings['data_handling']['purge_method'] === 'archive')>
                        Archive
                    </option>
                    <option value="delete" @selected($user->settings['data_handling']['purge_method'] === 'delete')>
                        Permanent Delete
                    </option>
                </select>
            </div>

            <div>
                <x-input-label :value="__('Purge Delay (minutes)')" />
                <x-text-input
                    type="number"
                    min="0"
                    name="settings[data_handling][delay_minutes]"
                    class="mt-1 block w-full"
                    :value="old('settings.data_handling.delay_minutes', $user->settings['data_handling']['delay_minutes'])"
                />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>
                {{ __('Save Settings') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
