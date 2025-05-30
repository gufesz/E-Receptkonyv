document.addEventListener('DOMContentLoaded', () => {
    loadRecipes();
    
    // Filter event listeners
    document.getElementById('categoryFilter').addEventListener('change', loadRecipes);
    document.getElementById('timeFilter').addEventListener('input', debounce(loadRecipes, 300));
    
    // Modal close
    document.querySelector('.close').addEventListener('click', () => {
        document.getElementById('recipeModal').style.display = 'none';
    });
});

async function loadRecipes() {
    const category = document.getElementById('categoryFilter').value;
    const maxTime = document.getElementById('timeFilter').value;
    
    let url = '../php/get_recipes.php';
    if (category || maxTime) {
        url += '?';
        if (category) url += `category=${encodeURIComponent(category)}`;
        if (category && maxTime) url += '&';
        if (maxTime) url += `max_time=${maxTime}`;
    }
    
    try {
        const response = await fetch(url);
        const recipes = await response.json();
        
        displayRecipes(recipes);
    } catch (error) {
        console.error('Error loading recipes:', error);
    }
}

function displayRecipes(recipes) {
    const container = document.getElementById('recipesContainer');
    
    if (recipes.length === 0) {
        container.innerHTML = '<p>Nincs megjeleníthető recept</p>';
        return;
    }
    
    container.innerHTML = recipes.map(recipe => `
        <div class="card" data-id="${recipe.id}">
            <img src="${recipe.image || 'https://via.placeholder.com/300x200?text=Nincs+kép'}" alt="${recipe.name}">
            <div class="card-content">
                <h3>${recipe.name}</h3>
                <p class="category">${recipe.category}</p>
                <p class="time">${recipe.time} perc</p>
                <button class="details-btn">Részletek</button>
            </div>
        </div>
    `).join('');
    
    // Add event listeners to details buttons
    document.querySelectorAll('.details-btn').forEach(btn => {
        btn.addEventListener('click', () => showRecipeDetails(btn.closest('.card').dataset.id));
    });
}

async function showRecipeDetails(recipeId) {
    try {
        const response = await fetch(`../php/get_recipe_details.php?id=${recipeId}`);
        const recipe = await response.json();
        
        // Fill modal with recipe data
        document.getElementById('recipeTitle').textContent = recipe.name;
        document.getElementById('recipeImage').src = recipe.image || 'https://via.placeholder.com/300x200?text=Nincs+kép';
        document.getElementById('recipeCategory').textContent = recipe.category;
        document.getElementById('recipeTime').textContent = recipe.time;
        
        // Format ingredients
        const ingredientsList = document.getElementById('ingredientsList');
        ingredientsList.innerHTML = recipe.ingredients.split('\n')
            .filter(line => line.trim())
            .map(line => `<li>${line}</li>`).join('');
        
        // Format instructions
        const instructionsList = document.getElementById('instructionsList');
        instructionsList.innerHTML = recipe.instructions.split('\n')
            .filter(line => line.trim())
            .map(line => `<li>${line}</li>`).join('');
        
        // Show modal
        document.getElementById('recipeModal').style.display = 'block';
    } catch (error) {
        console.error('Error loading recipe details:', error);
    }
}

function debounce(func, wait) {
    let timeout;
    return function() {
        const context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}