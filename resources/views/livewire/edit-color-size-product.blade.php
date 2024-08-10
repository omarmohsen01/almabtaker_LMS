<div>
    <div class="form-group">
        <label for="configurationOption">Configuration Option<span class="text-danger">*</span></label>
        <select name="size_color_type" wire:model="configurationOption" id="configurationOption" class="form-control">
            <option value="">Choose an option</option>
            <option value="size_depends_on_color" {{ ($product->size_color_type==='size_depends_on_color')?'selected':'' }}>Size depends on color</option>
            <option value="color_depends_on_size" {{ ($product->size_color_type==='color_depends_on_size')?'selected':'' }}>Color depends on size</option>
            <option value="color_only" {{ ($product->size_color_type==='color_only')?'selected':'' }}>Color only</option>
            <option value="size_only" {{ ($product->size_color_type==='size_only')?'selected':'' }}>Size only</option>
            <option value="without_any" {{ ($product->size_color_type==='without_any')?'selected':'' }}>Without any</option>
        </select>
    </div>

    <button wire:click="addAttribute" type="button" class="btn btn-primary btn-sm"
    @if ($configurationOption === 'without_any' || !$configurationOption) disabled @endif>
        Add Attribute
    </button>

    @foreach ($productAttributes as $index => $attribute)
        <div class="row mt-3">
            @if (in_array($configurationOption, ['size_depends_on_color', 'color_depends_on_size', 'color_only']))
                <div class="col">
                    <div class="form-group">
                        <label for="color_{{ $index }}">Color<span class="text-danger">*</span></label>
                        <select required name="product_size_color[{{ $index }}][color_id]"
                                wire:model="productAttributes.{{ $index }}.color_id" id="color_{{ $index }}" class="form-control">
                            <option value="">Choose a color</option>
                            @foreach ($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->title_en }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if (in_array($configurationOption, ['size_depends_on_color', 'color_depends_on_size', 'size_only']))
                <div class="col">
                    <div class="form-group">
                        <label for="size_{{ $index }}">Size<span class="text-danger">*</span></label>
                        <select required name="product_size_color[{{ $index }}][size_id]"
                                wire:model="productAttributes.{{ $index }}.size_id" id="size_{{ $index }}" class="form-control">
                            <option value="">Choose a size</option>
                            @foreach ($sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->title_en }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            <div class="col">
                <div class="form-group">
                    <label for="stock_{{ $index }}">Stock<span class="text-danger">*</span></label>
                    <input required name="product_size_color[{{ $index }}][stock]" min="0"
                           wire:model="productAttributes.{{ $index }}.stock" type="number" id="stock_{{ $index }}" class="form-control"
                           placeholder="Enter stock">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="real_price_{{ $index }}">Real Price<span class="text-danger">*</span></label>
                    <input required name="product_size_color[{{ $index }}][real_price]" min="0"
                           wire:model="productAttributes.{{ $index }}.real_price" type="number" id="real_price_{{ $index }}" class="form-control"
                           placeholder="Enter real price">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="fake_price_{{ $index }}">Fake Price</label>
                    <input name="product_size_color[{{ $index }}][fake_price]" min="0"
                           wire:model="productAttributes.{{ $index }}.fake_price" type="number" id="fake_price_{{ $index }}" class="form-control"
                           placeholder="Enter fake price">
                </div>
            </div>

            <div class="col-auto">
                <button wire:click="removeAttribute({{ $index }})" type="button" class="btn btn-danger btn-sm mt-4">Remove</button>
            </div>
        </div>
    @endforeach
</div>
