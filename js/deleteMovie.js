document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById("movieTableBody");

    tableBody.addEventListener('click', function (e) {
        const deleteBtn = e.target.closest('.btn-delete');
        if (!deleteBtn) return;

        const id = deleteBtn.dataset.id;
        if (!confirm("Are you sure you want to delete this movie?")) return;

        const body = new URLSearchParams();
        body.append('id', id);

        fetch('delete_movie.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById('movie-row-' + id);
                    if (row) row.remove();
                } else {
                    alert(data.error || 'Failed to delete movie.');
                }
            })
            .catch(() => alert('Network error while deleting.'));
    });
});
