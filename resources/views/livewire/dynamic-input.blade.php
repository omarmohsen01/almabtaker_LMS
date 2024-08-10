<div>
    <div class="button-group d-flex pt-20 justify-content-md-end justify-content-start">
        <button wire:click="add" type="button" class="btn btn-light btn-default btn-squared fw-400 text-capitalize radius-md">
            Add New Input Value
        </button>
    </div>
    <table >
        <thead >
            <tr>
                <td>-Input En</td>
                <td>-Input Ar</td>
                <td>-Value En</td>
                <td>-Value Ar</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            @foreach ($product_inputs as $index => $product_input)
                <tr class="bg-white border-b border-slate-200">
                    <td class="px-6 py-4">
                        <input name="product_inputs[{{ $index }}][input_en]"
                         value="{{ old('product_inputs.' . $index . '.input_en', $product_input['input_en']) }}"
                         required wire:model.defer="product_inputs.{{ $index }}.input_en" type="text" class="form-control" placeholder="Enter Input Key In En">
                        @error('product_inputs.'.$index.'.input_en')
                            <span class="text-sm text-pink-500">{{ $message }}</span>
                        @enderror
                    </td>
                    <td class="px-6 py-4">
                        <input name="product_inputs[{{ $index }}][input_ar]" required
                        value="{{ old('product_inputs.' . $index . '.input_ar', $product_input['input_ar']) }}"
                        wire:model.defer="product_inputs.{{ $index }}.input_ar" type="text" class="form-control" placeholder="Enter Input Key In Ar">
                        @error('product_inputs.'.$index.'.input_ar')
                            <span class="text-sm text-pink-500">{{ $message }}</span>
                        @enderror
                    </td>
                    <td class="px-6 py-4">
                        <input name="product_inputs[{{ $index }}][value_en]" required
                        value="{{ old('product_inputs.' . $index . '.value_en', $product_input['value_en']) }}"
                        wire:model.defer="product_inputs.{{ $index }}.value_en" type="text" class="form-control" placeholder="Enter Value In En">
                        @error('product_inputs.'.$index.'.value_en')
                            <span class="text-sm text-pink-500">{{ $message }}</span>
                        @enderror
                    </td>
                    <td class="px-6 py-4">
                        <input name="product_inputs[{{ $index }}][value_ar]"  required
                        value="{{ old('product_inputs.' . $index . '.value_ar', $product_input['value_ar']) }}"
                        wire:model.defer="product_inputs.{{ $index }}.value_ar" type="text" class="form-control" placeholder="Enter Value In Ar">
                        @error('product_inputs.'.$index.'.value_ar')
                            <span class="text-sm text-pink-500">{{ $message }}</span>
                        @enderror
                    </td>
                    <td class="px-6 py-4">
                        <button wire:click="delete({{ $index }})" type="button" class="btn btn-danger btn-default btn-squared fw-400 text-capitalize radius-md">
                            Remove
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
