document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById('movieTableBody');
    if (!tableBody) return;

    function sortTableByFavorite() {
        const rows = Array.from(tableBody.querySelectorAll('tr'));

        rows.sort((a, b) => {
            const favA = parseInt(a.querySelector('.btn-fav').dataset.fav);
            const favB = parseInt(b.querySelector('.btn-fav').dataset.fav);

            if (favA !== favB) return favB - favA;

            const nameA = a.children[0].textContent.trim().toLowerCase();
            const nameB = b.children[0].textContent.trim().toLowerCase();

            return nameA.localeCompare(nameB);
        });

        rows.forEach(r => tableBody.appendChild(r));
    }

    tableBody.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-fav');
        if (!btn) return;

        const id = btn.getAttribute('data-id');
        let fav = parseInt(btn.getAttribute('data-fav')) || 0;
        const newFav = fav ? 0 : 1;

        const body = new URLSearchParams();
        body.append('Movie_id', id);
        body.append('Favorite', newFav);

        fetch('toggle_favorite.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: body.toString()
        })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert(data.error || 'Failed to update favourite.');
                    return;
                }

                // update star
                btn.setAttribute('data-fav', data.favorite);
                btn.textContent = data.favorite ? '★' : '☆';

                // ⬇⬇ SORT AFTER UPDATE ⬇⬇
                sortTableByFavorite();
            })
            .catch(err => {
                console.error(err);
                alert('Network error while updating favourite.');
            });
    });
});
