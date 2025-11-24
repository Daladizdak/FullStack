document.addEventListener('DOMContentLoaded', () => {
    const msg = document.getElementById('successMessage');

    if (msg) {
        setTimeout(() => {
            msg.style.transition = "opacity 0.5s";
            msg.style.opacity = "0";
            setTimeout(() => msg.remove(), 500);
        }, 10000);
    }
});
