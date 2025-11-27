document.addEventListener('DOMContentLoaded', () => {
    const addMovieForm    = document.getElementById('addMovieForm');
    const addError        = document.getElementById('addError');
    const addMovieModalEl = document.getElementById('addMovieModal');


addMovieModalEl.addEventListener("shown.bs.modal", () => {

    const movieNameInput   = document.getElementById('MovieName');
    const duplicateWarning = document.getElementById('duplicateWarning');
    const submitButton     = addMovieForm.querySelector('button[type="submit"]');

    duplicateWarning.textContent = '';
    submitButton.disabled = false;

    movieNameInput.onkeyup = () => {
        const name = movieNameInput.value.trim();

        if (name.length < 2) {
            duplicateWarning.textContent = '';
            submitButton.disabled = false;
            return;
        }

        fetch('check-movie.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'MovieName=' + encodeURIComponent(name)
        })
        .then(res => res.json())
        .then(data => {

		if (data.error === 'You must be logged in') {
    			alert('Please log in to perform this action.');
   			 return;
		}





            if (data.exists) {
                duplicateWarning.textContent = '⚠ This movie already exists.';
                submitButton.disabled = true;
            } else {
                duplicateWarning.textContent = '';
                submitButton.disabled = false;
            }
        });
    };
});






    const addMovieModal   = addMovieModalEl ? new bootstrap.Modal(addMovieModalEl) : null;
    const tableBody       = document.getElementById("movieTableBody");

    if (!addMovieForm || !tableBody) return;



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

                const fav  = (parseInt(movie.Favorite ?? 0, 10) === 1) ? 1 : 0;
                const star = fav ? '★' : '☆';

                row.innerHTML = `
                    <td>${movie.Movie_name}</td>
                    <td>${movie.Genre}</td>
                    <td>${movie.Release_Date}</td>
                    <td class="movie-score">${parseInt(movie.Score, 10)}/100</td>
                    <td class="movie-fav">
                        <button
                            type="button"
                            class="btn btn-link btn-sm p-0 btn-fav"
                            data-id="${movie.Movie_id}"
                            data-fav="${fav}"
                        >
                            ${star}
                        </button>
                    </td>
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
                if (addMovieModal) addMovieModal.hide();

               
                showAddSuccessMessage();
            })
            .catch(() => {
                addError.textContent = 'Network error.';
            });
    });

   
    function showAddSuccessMessage() {
        let msg = document.getElementById('successMessage');

        
        if (!msg) {
            const container = document.querySelector('.container');
            msg = document.createElement('div');
            msg.id = 'successMessage';
            msg.className = 'alert alert-success mt-2';
            container.insertBefore(msg, container.children[1] || container.firstChild);
        }

        msg.textContent = 'Movie added successfully!';
        msg.style.display = 'block';
        msg.style.opacity = '1';

        // fade out after 3 seconds
        setTimeout(() => {
            msg.style.transition = 'opacity 0.5s';
            msg.style.opacity = '0';
            setTimeout(() => {
                msg.style.display = 'none';
                msg.style.opacity = '1';
            }, 500);
        }, 3000);
    }
});
