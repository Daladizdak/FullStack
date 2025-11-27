document.addEventListener('DOMContentLoaded', () => {
    console.log("editMovie.js loaded"); 

    const editMovieForm    = document.getElementById('editMovieForm');
    const editError        = document.getElementById('editError');
    const editMovieModalEl = document.getElementById('editMovieModal');
    const editMovieModal   = editMovieModalEl ? new bootstrap.Modal(editMovieModalEl) : null;
    const tableBody        = document.getElementById("movieTableBody");

    if (!editMovieForm || !editMovieModalEl || !tableBody) {
        console.warn("editMovie.js: missing elements");
        return;
    }


    tableBody.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.btn-edit');
        if (!editBtn) return;

        console.log("Edit button clicked:", editBtn.dataset);

        document.getElementById('EditMovieId').value       = editBtn.dataset.id;
        document.getElementById('EditMovieName').value     = editBtn.dataset.name;
        document.getElementById('EditGenre').value         = editBtn.dataset.genre;
        document.getElementById('EditReleaseDate').value   = editBtn.dataset.date;
        document.getElementById('EditScore').value         = editBtn.dataset.score;

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

			if (data.error === 'You must be logged in') {
    			alert('Please log in to perform this action.');
   			 return;
		}



                console.log("update_movie.php returned:", data);

                if (!data.success) {
                    editError.textContent = data.error || "Failed to update movie.";
                    return;
                }

                
                editMovieModal.hide();

                
                window.location.reload();
            })
            .catch(err => {
                console.error("Network error:", err);
                editError.textContent = "Network error.";
            });
    });
});
