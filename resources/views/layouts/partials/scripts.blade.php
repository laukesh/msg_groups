<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const sidebar = document.querySelector('.app-sidebar');
    const toggle  = document.getElementById('sidebarToggle');

    if (toggle && sidebar) {
        toggle.addEventListener('click', function () {
            sidebar.classList.toggle('show');
        });
    }

    const dateChip = document.getElementById('datechip');

    if (dateChip) {
        dateChip.textContent = new Date().toLocaleDateString('en-GB', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    }
});
</script>
