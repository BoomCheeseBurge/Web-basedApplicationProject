        <script src="JS/script.js"></script>
        <script>
            // Enable tooltips everywhere
            // One way to initialize all tooltips on a page would be to select them by their data-bs-toggle attribute
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        </script>
    </body>
</html>