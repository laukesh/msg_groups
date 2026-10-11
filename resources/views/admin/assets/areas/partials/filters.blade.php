<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Filters</h5>
    </div>

    <div class="card-body">

        <form method="GET"
              action="{{ $action ?? url()->current() }}">

            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label">Mall</label>

                    <select name="mall_id" class="form-select">
                        <option value="">All Malls</option>

                        @foreach($malls ?? [] as $mall)
                            <option value="{{ $mall->id }}"
                                @selected(request('mall_id') == $mall->id)>
                                {{ $mall->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Building</label>

                    <select name="building_id" class="form-select">
                        <option value="">All Buildings</option>

                        @foreach($buildings ?? [] as $building)
                            <option value="{{ $building->id }}"
                                @selected(request('building_id') == $building->id)>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Floor</label>

                    <select name="floor_id" class="form-select">
                        <option value="">All Floors</option>

                        @foreach($floors ?? [] as $floor)
                            <option value="{{ $floor->id }}"
                                @selected(request('floor_id') == $floor->id)>
                                {{ $floor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Area Type</label>

                    <select name="area_type" class="form-select">
                        <option value="">All Types</option>

                        @foreach($areaTypes ?? [] as $type)
                            <option value="{{ $type->name }}"
                                @selected(request('area_type') === $type->name)>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>

                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>

                        @foreach([
                            'ACTIVE' => 'Active',
                            'INACTIVE' => 'Inactive',
                        ] as $value => $label)

                            <option value="{{ $value }}"
                                @selected(request('status') === $value)>
                                {{ $label }}
                            </option>

                        @endforeach
                    </select>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        Filter
                    </button>
                </div>

            </div>

            <div class="mt-3">
                <a href="{{ $action ?? url()->current() }}"
                   class="btn btn-sm btn-outline-secondary">
                    Reset Filters
                </a>
            </div>

        </form>

    </div>
</div>
