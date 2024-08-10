<div class="row">
    <div class="form-group col-4">
        <label for="department">Department</label>
        <select wire:model="selectedDepartment" name="department_id" class="form-control">
            <option value="" selected>Choose department</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ $selectedDepartment == $department->id ? 'selected' : '' }}>
                    {{ $department->title_en }}
                </option>
            @endforeach
        </select>
    </div>

    @if (!is_null($selectedDepartment))
        <div class="form-group col-4">
            <label for="mainCategory">Main Category</label>
            <select name="main_category_id" wire:model="selectedMainCategory" class="form-control">
                <option value="" selected>Choose main category</option>
                @if ($mainCategories)
                    @foreach($mainCategories as $mainCategory)
                        <option value="{{ $mainCategory->id }}" {{ $selectedMainCategory == $mainCategory->id ? 'selected' : '' }}>
                            {{ $mainCategory->title_en }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    @endif

    @if (!is_null($selectedMainCategory))
        <div class="form-group col-4">
            <label for="subCategory">Sub Category</label>
            <select name="sub_category_id" wire:model="selectedSubCategory" class="form-control">
                <option value="" selected>Choose sub category</option>
                @if ($subCategories)
                    @foreach($subCategories as $subCategory)
                        <option value="{{ $subCategory->id }}" {{ $selectedSubCategory == $subCategory->id ? 'selected' : '' }}>
                            {{ $subCategory->title_en }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    @endif
</div>
