<div class="form-step">
    <div class="user-tab-info-title mb-sm-40 mb-20 text-capitalize">
        <h5 class="fw-500">Images</h5>
        <div class="card add-product p-sm-30 p-20">
            <div class="card-body p-0">
                <div class="card-header">
                    <h6 class="fw-500">Product image</h6>
                </div>
                <!-- Start: product body -->
                <div class="add-product__body-img px-sm-40 px-20">
                    <label for="productImage">Image:</label>
                    <label for="productImage" class="file-upload__label">
                        <span class="upload-product-img px-10 d-block">
                            <span class="file-upload">
                                <img class="svg" src="img/svg/upload.svg" alt="">
                                <input id="productImage" class="file-upload__input" type="file" wire:model="productImage">
                            </span>
                            <span class="pera">Drag and drop an image</span>
                            <span>or <a href="#" class="color-secondary">Browse</a> to choose a file</span>
                        </span>
                    </label>
                    @error('productImage') <span class="error">{{ $message }}</span> @enderror
                    @if ($productImage)
                        <div class="mt-2">
                            <img src="{{ $productImage->temporaryUrl() }}" width="200" alt="Product Image Preview">
                        </div>
                    @endif
                </div>
                <!-- End: product body -->
                <!-- Start: slider body -->
                <div class="add-product__body-img px-sm-40 px-20">
                    <label for="sliderImages">Slider:</label>
                    <label for="sliderImages" class="file-upload__label">
                        <span class="upload-product-img px-10 d-block">
                            <span class="file-upload">
                                <img class="svg" src="img/svg/upload.svg" alt="">
                                <input multiple id="sliderImages" class="file-upload__input" type="file" wire:model="sliderImages">
                            </span>
                            <span class="pera">Drag and drop an image</span>
                            <span>or <a href="#" class="color-secondary">Browse</a> to choose a file</span>
                        </span>
                    </label>
                    @error('sliderImages.*') <span class="error">{{ $message }}</span> @enderror
                    @if ($sliderImages)
                        <div class="mt-2">
                            @foreach ($sliderImages as $sliderImage)
                                <img src="{{ $sliderImage->temporaryUrl() }}" width="200" alt="Slider Image Preview">
                            @endforeach
                        </div>
                    @endif
                </div>
                <!-- End: slider body -->
            </div>
        </div>
    </div>
</div>
