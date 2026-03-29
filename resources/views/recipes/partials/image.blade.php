<div class="section-heading">
    <span class="icon">🖼</span> Photo
</div>

<div class="field-group">
    <div class="image-upload-zone">
        <input type="file" name="image" accept="image/*">
        <span class="upload-icon">📷</span>
        <p class="upload-text">Click to upload a photo</p>
        <p class="upload-hint">JPEG, PNG, WebP — max 2 MB</p>
    </div>
    @error('image')<p class="error-msg">{{ $message }}</p>@enderror
</div>
