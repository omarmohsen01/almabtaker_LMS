<div>
    <div class="form-group row">
        <label for="country" class="col-md-4 col-form-label text-md-right">{{ __('Country') }}</label>

        <div class="col-md-6">
            <select wire:model.live="selectedCountry" class="form-control">
                <option name="country_id" value="" selected>Choose country</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->title_en }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @if (!is_null($selectedCountry))
        <div class="form-group row">
            <label for="city" class="col-md-4 col-form-label text-md-right">{{ __('City') }}</label>
            <div class="col-md-6">
                <select name="city_id" wire:model.live="selectedCity" class="form-control">
                    <option value="" selected>Choose city</option>
                        @if ($cities)
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->title_en }}</option>
                            @endforeach
                        @endif
                </select>
            </div>
        </div>
    @endif
    @if (!is_null($selectedCity))
    <div class="form-group row">
        <label for="region" class="col-md-4 col-form-label text-md-right">{{ __('Region') }}</label>
        <div class="col-md-6">
            <select name="region_id" class="form-control">
                <option value="" selected>Choose region</option>
                    @if($regions)
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->title_en }}</option>
                        @endforeach
                    @endif
            </select>
        </div>
    </div>
    @endif
</div>

