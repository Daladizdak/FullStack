document.addEventListener('DOMContentLoaded', () => {
    const searchBox = document.getElementById("searchBox");
    const tableBody = document.getElementById("movieTableBody");
    const genreFilter  = document.getElementById("genreFilter");
    const yearBeforeEl = document.getElementById("yearBefore");
    const noResultsMessage = document.getElementById("noResultsMessage");
    const suggestions = document.getElementById("suggestions");
    const spinner = document.getElementById("spinner");

    if (!searchBox) return;

    function clearSuggestions() {
        suggestions.innerHTML = '';
    }

    function renderSuggestions(movies) {
        clearSuggestions();

        const keywords = searchBox.value.trim();
        if (keywords.length < 2) return;

        movies.slice(0, 5).forEach(movie => {
            const li = document.createElement('li');
            li.className = 'list-group-item list-group-item-action';
            li.textContent = movie.Movie_name;

            li.addEventListener('click', () => {
                searchBox.value = movie.Movie_name;
                clearSuggestions();
                doSearch();
            });

            suggestions.appendChild(li);
        });
    }

function doSearch() {
    const keywords   = searchBox.value;
    const genre      = genreFilter ? genreFilter.value.trim() : '';
    const yearBefore = yearBeforeEl ? yearBeforeEl.value.trim() : '';

    const params = new URLSearchParams();
    params.append('search', keywords);

    if (genre !== '') {
        params.append('genre', genre);
    }
    if (yearBefore !== '') {
        params.append('year_before', yearBefore);
    }

    spinner.style.display = 'inline-block';

    fetch('search_movies.php?' + params.toString())
        .then(res => res.json())
        .then(movies => {
            spinner.style.display = 'none';
            tableBody.innerHTML = '';

            if (!movies.length) {
                noResultsMessage.style.display = 'block';
                clearSuggestions();
                return;
            }

            noResultsMessage.style.display = 'none';

            movies.forEach(movie => {
                const row = document.createElement('tr');
                row.id = 'movie-row-' + movie.Movie_id;

                const fav  = (parseInt(movie.Favorite ?? 0, 10) === 1) ? 1 : 0;
                const star = fav ? '★' : '☆';

                row.innerHTML = `
                    <td>${movie.Movie_name}</td>
                    <td>${movie.Genre}</td>
                    <td>${movie.Release_Date}</td>
                    <td class="movie-score">${parseInt(movie.Score, 10)}/100</td>
                    <td class="movie-fav">
                        ${
                            (typeof LOGGED_IN !== 'undefined' && (LOGGED_IN === true || LOGGED_IN === 'true'))
                            ? `<button
                                    type="button"
                                    class="btn btn-link btn-sm p-0 btn-fav"
                                    data-id="${movie.Movie_id}"
                                    data-fav="${fav}">
                                    ${star}
                               </button>`
                            : star
                        }
                    </td>
                    <td>
                        ${
                            (typeof LOGGED_IN !== 'undefined' && (LOGGED_IN === true || LOGGED_IN === 'true'))
                            ? `<button class="btn btn-sm btn-primary btn-edit"
                                        data-id="${movie.Movie_id}"
                                        data-name="${movie.Movie_name}"
                                        data-genre="${movie.Genre}"
                                        data-date="${movie.Release_Date}"
                                        data-score="${movie.Score}">
                                    Edit
                               </button>
                               <button class="btn btn-sm btn-danger btn-delete"
                                        data-id="${movie.Movie_id}">
                                    Delete
                               </button>`
                            : `<span class="text-muted small">Login to edit</span>`
                        }
                    </td>
                `;

                tableBody.appendChild(row);
            });

            renderSuggestions(movies);
        })
        .catch(() => {
            spinner.style.display = 'none';
            clearSuggestions();
        });
}


        searchBox.addEventListener("keyup", doSearch);
	if (genreFilter)  genreFilter.addEventListener("keyup", doSearch);
	if (yearBeforeEl) yearBeforeEl.addEventListener("keyup", doSearch);

    document.addEventListener('click', (e) => {
        if (!searchBox.contains(e.target) && !suggestions.contains(e.target)) {
            clearSuggestions();
        }
    });
});
