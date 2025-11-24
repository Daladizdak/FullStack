document.addEventListener('DOMContentLoaded', () => {
    const addMovieForm = document.getElementById('addMovieForm');
    const addError = document.getElementById('addError');
    const addMovieModalEl = document.getElementById('addMovieModal');
    const addMovieModal = addMovieModalEl ? new bootstrap.Modal(addMovieModalEl) : null;
    const tableBody = document.getElementById("movieTableBody");

    if (!addMovieForm) return;

    addMovieForm.addEventListener('submit', (e) => {
        e.preventDefault();
        addError.textContent = '';

        const formData = new URLSearchParams(new FormData(addMovieForm));

        fetch('add_movie.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    addError.textContent = data.error || 'Something went wrong.';
                    return;
                }

                const movie = data.movie;

                const row = document.createElement('tr');
                row.id = 'movie-row-' + movie.Movie_id;
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

                tableBody.appendChild(row);
                addMovieForm.reset();
                addMovieModal.hide();
            })
            .catch(() => addError.textContent = 'Network error.');
    });
});
