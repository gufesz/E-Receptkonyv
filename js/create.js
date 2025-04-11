const form = document.getElementById('ingredientForm');

form.addEventListener('submit', function(e) {
    let valid = true;

    const ingredientInputs = document.querySelectorAll('input[name^="ingredients["][name$="][name]"]');
    if (ingredientInputs.length === 0) {
        alert('Legalább egy hozzávalót meg kell adni!');
        valid = false;
    } else {
        ingredientInputs.forEach(input => {
            if (!input.value.trim()) {
                alert('Minden hozzávalónak meg kell adni a nevét!');
                valid = false;
            }
        });
    }

    if (!valid) {
        e.preventDefault();
    } else {
        alert('Sikeresen mentve!');
    }
});

let ingredientIndex = 1;
function addIngredient() {
    const container = document.getElementById('ingredientsContainer');
    const div = document.createElement('div');
    div.innerHTML = `<input class="input" type="text" name="ingredients[${ingredientIndex}][name]" placeholder="Hozzávaló neve">`;
    container.appendChild(div);
    ingredientIndex++;
}