document.addEventListener('DOMContentLoaded', () => {
    const editMovieForm = document.getElementById('editMovieForm');
    const editError = document.getElementById('editError');
    const editMovieModalEl = document.getElementById('editMovieModal');
    const editMovieModal = editMovieModalEl ? new bootstrap.Modal(editMovieModalEl) : null;

    const tableBody = document.getElementById("movieTableBody");

    tableBody.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.btn-edit');
        if (!editBtn) return;

        document.getElementById('EditMovieId').value = editBtn.dataset.id;
        document.getElementById('EditMovieName').value = editBtn.dataset.name;
        document.getElementById('EditGenre').value = editBtn.dataset.genre;
        document.getElementById('EditReleaseDate').value = editBtn.dataset.date;
        document.getElementById('EditScore').value = editBtn.dataset.score;

        editError.textContent = '';
        editMovieModal.show();
    });

    editMovieForm.addEventListener('submit', (e) => {
        e.preventDefault();
        editError.textContent = '';

        const body = new URLSearchParams(new FormData(editMovieForm));

        fetch('update_movie.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body
        })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    editError.textContent = data.error || 'Failed to update movie.';
                    return;
                }

                const movie = data.movie;
                const row = document.getElementById('movie-row-' + movie.Movie_id);

                row.innerHTML = `
                    <td>${movie.Movie_name}</td>
                    <td>${movie.Genre}</td>
                    <td>${movie.Release_Date}</td>
                    <td>${movie.Score}/100</td>
                    <td>
                        <button class="btn btn-sm btn-primary btn-edit"
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
                        </button>
                    </td>
                `;

                editMovieModal.hide();
            })
            .catch(() => editError.textContent = 'Network error.');
    });
});
