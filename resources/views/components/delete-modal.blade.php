@props(['id', 'action', 'title' => 'Löschen bestätigen', 'message' => 'Sind Sie sicher, dass Sie diesen Eintrag löschen möchten?'])

<div x-data="{ open: false }" @open-delete-modal-{{ $id }}.window="open = true">
    <template x-teleport="body">
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[9999]"
            x-cloak
            role="dialog"
            aria-modal="true"
        >
            <div class="absolute inset-0 bg-black/30" @click="open = false"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div
                    x-show="open"
                    @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="bg-white rounded-lg shadow-xl overflow-hidden shrink-0 w-[28rem] max-w-[90vw]"
                >
                    <div class="px-6 py-4">
                        <div class="flex items-start">
                            <div class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>
                                <p class="mt-2 text-sm text-gray-500">{{ $message }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex flex-row-reverse gap-2">
                        <form method="POST" action="{{ $action }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Löschen
                            </button>
                        </form>
                        <button type="button" @click="open = false"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-steiner-blue">
                            Abbrechen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
