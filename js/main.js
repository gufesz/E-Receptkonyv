// Bejelentkezés ellenőrzése
async function checkAuth() {
    try {
        const response = await fetch('api/check_session.php');
        const data = await response.json();
        if (!data.loggedIn) window.location.href = 'login.html';
    } catch (error) {
        console.error('Auth error:', error);
    }
}

// Receptek betöltése
async function loadRecipes() {
    await checkAuth();
    
    try {
        const response = await fetch('api/get_recipes.php');
        const recipes = await response.json();
        
        const container = document.querySelector('.recipes-container');
        container.innerHTML = recipes.map(recipe => `
            <div class="card" data-id="${recipe.id}">
                <img src="${recipe.image || 'img/placeholder.jpg'}" alt="${recipe.name}">
                <h3>${recipe.name}</h3>
                <p class="category">${recipe.category}</p>
                <button class="details-btn">Részletek</button>
            </div>
        `).join('');
        
        // Eseménykezelők
        document.querySelectorAll('.details-btn').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const recipeId = e.target.closest('.card').dataset.id;
                await showRecipeDetails(recipeId);
            });
        });
    } catch (error) {
        console.error('Error loading recipes:', error);
    }
}

// Recept részletek
async function showRecipeDetails(recipeId) {
    try {
        const response = await fetch(`api/get_recipe_details.php?id=${recipeId}`);
        const recipe = await response.json();
        
        // Modal feltöltése
        document.getElementById('recipeTitle').textContent = recipe.name;
        document.getElementById('recipeImage').src = recipe.image || 'img/placeholder.jpg';
        
        const ingredientsList = document.getElementById('ingredientsList');
        ingredientsList.innerHTML = recipe.ingredients.split('\n')
            .filter(line => line.trim())
            .map(line => `<li>${line}</li>`).join('');
        
        // ... hasonlóan a többi adat
    } catch (error) {
        console.error('Error loading recipe details:', error);
    }
}

// Oldal betöltése
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.recipes-container')) {
        loadRecipes();
    }
});