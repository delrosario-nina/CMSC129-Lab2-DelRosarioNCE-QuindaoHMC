<div class="section-heading">
    <span class="icon">📝</span> Basic Info
</div>

<div class="field-group">
    <label class="field-label">Recipe Title <span class="required">*</span></label>
    <input type="text" name="title" value="{{ old('title') }}"
        placeholder="e.g. Grandma's Chocolate Cake"
        class="form-input {{ $errors->has('title') ? 'error' : '' }}">
    @error('title')<p class="error-msg">{{ $message }}</p>@enderror
</div>

<div class="field-group">
    <label class="field-label">Description <span class="required">*</span></label>
    <textarea name="description" rows="3"
        placeholder="A brief, appetizing description..."
        class="form-textarea {{ $errors->has('description') ? 'error' : '' }}">{{ old('description') }}</textarea>
    @error('description')<p class="error-msg">{{ $message }}</p>@enderror
</div>
