<div x-data="{ ingredients: [{ name: '', amount: '' }] }">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
        <div class="section-heading" style="margin-bottom:0;">
            <span class="icon">🧂</span> Ingredients <span style="color:#f87171;">*</span>
        </div>
        <button type="button" class="btn-add-row"
            @click="ingredients.push({ name: '', amount: '' })">+ Add Ingredient</button>
    </div>

    @error('ingredients')<p class="error-msg" style="margin-bottom:0.75rem;">{{ $message }}</p>@enderror

    <div class="space-y">
        <template x-for="(ingredient, index) in ingredients" :key="index">
            <div class="ingredient-row">
                <input type="text" :name="`ingredients[${index}][amount]`"
                    placeholder="Amount (e.g. 2 cups)" x-model="ingredient.amount"
                    class="form-input amount-input">
                <input type="text" :name="`ingredients[${index}][name]`"
                    placeholder="Ingredient name" x-model="ingredient.name"
                    class="form-input name-input">
                <button type="button" class="btn-remove"
                    @click="ingredients.splice(index, 1)"
                    x-show="ingredients.length > 1">✕</button>
            </div>
        </template>
    </div>
</div>
