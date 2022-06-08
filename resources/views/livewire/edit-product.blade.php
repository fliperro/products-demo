<div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">

        <x-validation-errors />

        <form wire:submit.prevent="save" class="space-y-8 divide-y divide-gray-200 sm:space-y-5">

            <div
                x-data="{ format: {numeral: true, numeralDecimalMark: '.', delimiter: ''}}"
                class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

                <div class="col-span-6 sm:col-span-3">
                    <label for="product-name" class="block text-sm font-medium text-gray-700"> Nazwa </label>
                    <div class="mt-1">
                        <input wire:model="name" type="text" id="product-name" autocomplete="product-name" class="shadow-sm block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>

                <div class="col-span-6 sm:col-span-6">
                    <label for="description" class="block text-sm font-medium text-gray-700"> Opis </label>
                    <div class="mt-1">
                        <textarea wire:model="description" id="description" rows="3" class="shadow-sm focus:ring-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                    </div>
                </div>

                <div class="col-span-6 md:col-span-2">
                    @foreach ($prices as $index => $price)

                        <div class="flex flex-col mt-4">
                            <label for="price-{{ $index }}" class="block text-sm font-medium text-gray-700"> Cena #{{ $index + 1 }} </label>
                            <div class="flex justify-start">
                                <input x-mask="format" type="text" wire:model="prices.{{ $index }}.value" placeholder="0.00" class="shadow-sm block w-full sm:text-sm border-gray-300 rounded-md">
                                @if($index !== 0)
                                    <button wire:click="removePriceInput({{ $index }})" type="button" class="ml-3 inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200">Usuń</button>
                                @endif
                            </div>
                        </div>

                    @endforeach
                </div>

                <div class="col-span-6">
                    <button wire:click="addBlankPriceInput()" type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200">Dodaj cenę</button>
                </div>

            </div>

            <div class="pt-5">
                <div class="flex justify-end">
                  <a href="{{ route('products.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Anuluj</a>
                  <button type="submit" wire:loading.attr="disabled" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Zapisz</button>
                </div>
            </div>

        </form>

    </div>

</div>
