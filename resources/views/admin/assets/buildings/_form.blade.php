<div class="mb-3">
    <label class="form-label">Mall</label>
    <select name="mall_id" class="form-control" required>
        <option value="">Select Mall</option>
        @foreach($malls as $id => $name)
            <option value="{{ $id }}" {{ (old('mall_id', optional($building)->mall_id) == $id) ? 'selected' : '' }}>{{ $name }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Building Code</label>
    <input type="text" name="building_code" value="{{ old('building_code', optional($building)->building_code) }}" class="form-control" required>
</div>

<div class="mb-3">
    <label class="form-label">Building Name</label>
    <input type="text" name="building_name" value="{{ old('building_name', optional($building)->building_name) }}" class="form-control" required>
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control">{{ old('description', optional($building)->description) }}</textarea>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Total Floors</label>
        <input type="number" name="total_floors" value="{{ old('total_floors', optional($building)->total_floors) }}" class="form-control">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Total Units</label>
        <input type="number" name="total_units" value="{{ old('total_units', optional($building)->total_units) }}" class="form-control">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Status</label>
        <input type="text" name="status" value="{{ old('status', optional($building)->status) }}" class="form-control">
    </div>
</div>
