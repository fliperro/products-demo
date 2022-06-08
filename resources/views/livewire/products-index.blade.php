<div>
    <div>
        <div class="px-4 py-5 sm:p-6">

            <!-- topbar -->
            <div class="md:flex md:items-center md:justify-between">
                <div class="sm:flex">
                    <div>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-heroicon-s-search class="h-5 w-5 text-gray-400" />
                            </div>
                            <input type="search" wire:model.debounce="search" class="block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Szukaj (min 3 znaki)" autocomplete="off">
                        </div>
                    </div>
                    <div class="mt-3 sm:mt-0 ml-0 sm:ml-3 flex items-center">
                        <input type="number" wire:model.debounce="minPrice" class="block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Cena od..." autocomplete="off">
                        <input type="number" wire:model.debounce="maxPrice" class="ml-3 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Cena do..." autocomplete="off">
                    </div>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4 justify-between md:justify-start">

                    @auth
                        <a href="{{ route('products.create') }}" class="mr-1 inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 sm:w-auto">Nowy Produkt</a>
                    @endauth

                    <x-dropdown align="right" width="60">
                        <x-slot name="trigger">
                            <button type="button" class="relative inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <span class="hidden sm:block">Sortowanie</span>
                                <x-heroicon-s-sort-descending class="mr-0 ml-0 sm:-mr-1 sm:ml-2 h-5 w-5 text-gray-400" />
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="w-60">
                                @foreach($allowedSort as $key => $value)
                                    <x-dropdown-link href="#" wire:click="$set('sort', '{{ $key }}')">{{ $value['label'] }}</x-dropdown-link>
                                @endforeach
                            </div>
                        </x-slot>
                    </x-dropdown>


                </div>
            </div>

        </div>
    </div>

    <div class="mt-4">

        @if($products->isNotEmpty())
            <div class="mt-8 flex flex-col">
                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Nazwa</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Cena (min)</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Cena (max)</th>
                                        <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Ilość cen</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Data dodania</th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach($products as $product)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                <a href="{{ route('products.show', $product->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    {{ $product->name }}
                                                </a>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->lowestPrice->value ?? '-' }} zł</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->highestPrice->value ?? '-' }} zł</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->prices_count }} </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->created_at->diffForHumans() }} </td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                @auth
                                                    <a href="{{ route('products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900">Edytuj</a>

                                                    <button type="button" wire:click="showDeleteModal({{$product->id}})" class="ml-2 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Usuń</a>
                                                @endauth
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>

        @else
            Brak produktów
        @endif
    </div>

    <form wire:submit.prevent="deleteSelectedProduct">
        <x-modal.confirmation wire:model.defer="showDeleteModal">
            <x-slot name="title">Usuwanie Produktu</x-slot>

            <x-slot name="content">
                <div class="py-8 text-cool-gray-700">Czy chcesz usunąć produkt? </div>
            </x-slot>

            <x-slot name="footer">
                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Usuń</button>
                <button type="button" wire:click="$set('showDeleteModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Anuluj</button>
            </x-slot>
        </x-modal.confirmation>
    </form>
</div>
