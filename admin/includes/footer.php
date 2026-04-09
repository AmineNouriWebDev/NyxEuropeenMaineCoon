    </div>
    <!-- Content Container end -->
</div>
<!-- Main Content Wrapper end -->

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Scripts d'Admin -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Toggle Sidebar Mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                sidebar.classList.toggle('active');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.toggle('active');
                }
            });
        }

        if (sidebarOverlay && sidebar) {
            sidebarOverlay.addEventListener('click', function(e) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            });
        }
    });
</script>

</body>
</html>
