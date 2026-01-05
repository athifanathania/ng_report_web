<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-x-3">
            {{-- Ikon Tangan Melambai --}}
            <div class="p-2 bg-primary-50 rounded-full">
                <x-heroicon-o-hand-raised class="w-8 h-8 text-primary-600" />
            </div>

            <div>
                {{-- Sapaan Nama User --}}
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Selamat Datang, {{ auth()->user()->name }}!
                </h2>

                {{-- Menampilkan Role --}}
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Anda login sebagai: 
                    <span class="font-semibold text-primary-600">
                        {{ auth()->user()->role }}
                    </span>
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>