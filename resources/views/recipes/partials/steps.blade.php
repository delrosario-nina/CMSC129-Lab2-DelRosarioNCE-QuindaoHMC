<div x-data="{ steps: [{ title: '', instruction: '' }] }">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
        <div class="section-heading" style="margin-bottom:0;">
            <span class="icon">👨‍🍳</span> Steps <span style="color:#f87171;">*</span>
        </div>
        <button type="button" class="btn-add-row"
            @click="steps.push({ title: '', instruction: '' })">+ Add Step</button>
    </div>

    @error('steps')<p class="error-msg" style="margin-bottom:0.75rem;">{{ $message }}</p>@enderror

    <div class="space-y-lg">
        <template x-for="(step, index) in steps" :key="index">
            <div class="step-row">
                <div class="step-number" x-text="index + 1"></div>
                <div class="step-fields">
                    <input type="text" :name="`steps[${index}][title]`"
                        placeholder="Step title (e.g. Prepare the batter)"
                        x-model="step.title" class="form-input">
                    <textarea :name="`steps[${index}][instruction]`"
                        placeholder="Describe what to do in this step..."
                        x-model="step.instruction" rows="2" class="form-textarea"></textarea>
                </div>
                <button type="button" class="btn-remove"
                    @click="steps.splice(index, 1)"
                    x-show="steps.length > 1" style="margin-top:0.5rem;">✕</button>
            </div>
        </template>
    </div>
</div>
