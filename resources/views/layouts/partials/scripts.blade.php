<script>
    function toggleSidebar() {
        document
            .getElementById("sidebar")
            .classList.toggle("-translate-x-full");
        document.getElementById("overlay").classList.toggle("hidden");
    }
</script>

<script>
    function toggleProfileDropdown() {
        document
            .getElementById("profileDropdown")
            .classList.toggle("hidden");
    }

    // Close dropdown when clicking outside
    document.addEventListener("click", function(event) {
        const dropdown = document.getElementById("profileDropdown");
        const avatar = event.target.closest("button");

        if (!event.target.closest("#profileDropdown") && !avatar) {
            dropdown.classList.add("hidden");
        }
    });
</script>

{{-- SweetAlert Notifikasi --}}
@if (session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        })
    </script>
@endif

@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ $errors->first() }}'
        })
    </script>
@endif
