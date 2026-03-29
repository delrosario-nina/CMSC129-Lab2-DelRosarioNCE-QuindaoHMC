<div class="section-heading">
    <span class="icon">⏱</span> Time & Difficulty
</div>

<div class="grid-3">
    <div class="field-group">
        <label class="field-label">Prep Time (min) <span class="required">*</span></label>
        <input type="number" name="prep_time" value="{{ old('prep_time') }}" min="0"
            placeholder="15" class="form-input {{ $errors->has('prep_time') ? 'error' : '' }}">
        @error('prep_time')<p class="error-msg">{{ $message }}</p>@enderror
    </div>
    <div class="field-group">
        <label class="field-label">Cook Time (min) <span class="required">*</span></label>
        <input type="number" name="cook_time" value="{{ old('cook_time') }}" min="0"
            placeholder="30" class="form-input {{ $errors->has('cook_time') ? 'error' : '' }}">
        @error('cook_time')<p class="error-msg">{{ $message }}</p>@enderror
    </div>
    <div class="field-group">
        <label class="field-label">Difficulty <span class="required">*</span></label>
        <select name="difficulty" class="form-select">
            @foreach(['easy' => '🟢 Easy', 'medium' => '🟡 Medium', 'hard' => '🔴 Hard'] as $value => $label)
                <option value="{{ $value }}" {{ old('difficulty', 'easy') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>
