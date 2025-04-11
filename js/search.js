const searchForm = document.getElementById('searchForm');
const resultsDiv = document.getElementById('searchResults');

// Teszt adatok
const ingredientsList = ["Liszt", "Cukor", "Só", "Tojás", "Tej"];

searchForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const query = document.getElementById('searchInput').value.toLowerCase().trim();
    resultsDiv.innerHTML = "";

    const matches = ingredientsList.filter(item => item.toLowerCase().includes(query));
    if (matches.length > 0) {
        matches.forEach(item => {
            const p = document.createElement('p');
            p.textContent = item;
            resultsDiv.appendChild(p);
        });
    } else {
        resultsDiv.innerHTML = "<p>Nincs találat.</p>";
    }
});