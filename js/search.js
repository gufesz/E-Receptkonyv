document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    
    // Search event listener with debounce
    searchInput.addEventListener('input', debounce(searchRecipes, 300));
    
    async function searchRecipes() {
        const query = searchInput.value.trim();
        
        if (query.length < 2) {
            searchResults.innerHTML = '';
            return;
        }
        
        try {
            const response = await fetch(`../php/search_recipes.php?query=${encodeURIComponent(query)}`);
            const recipes = await response.json();
            
            displayResults(recipes);
        } catch (error) {
            console.error('Search error:', error);
            searchResults.innerHTML = '<p>Hiba történt a keresés során</p>';
        }
    }
    
    function displayResults(recipes) {
        if (recipes.length === 0) {
            searchResults.innerHTML = '<p>Nincs találat</p>';
            return;
        }
        
        searchResults.innerHTML = recipes.map(recipe => `
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
            
            // Create modal dynamically
            const modalHTML = `
                <div id="recipeModal" class="modal" style="display:block">
                    <div class="modal-content">
                        <span class="close" onclick="document.getElementById('recipeModal').remove()">&times;</span>
                        <h2>${recipe.name}</h2>
                        <img src="${recipe.image || 'https://via.placeholder.com/300x200?text=Nincs+kép'}" alt="${recipe.name}" style="max-width:100%; height:auto; border-radius:8px;">
                        <div class="recipe-details">
                            <h3>Hozzávalók</h3>
                            <ul>
                                ${recipe.ingredients.split('\n').filter(line => line.trim()).map(line => `<li>${line}</li>`).join('')}
                            </ul>
                            <h3>Elkészítés</h3>
                            <ol>
                                ${recipe.instructions.split('\n').filter(line => line.trim()).map(line => `<li>${line}</li>`).join('')}
                            </ol>
                            <p><strong>Kategória:</strong> ${recipe.category}</p>
                            <p><strong>Elkészítési idő:</strong> ${recipe.time} perc</p>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHTML);
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
});