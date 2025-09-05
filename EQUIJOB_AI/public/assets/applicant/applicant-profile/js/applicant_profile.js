        function openModal() {
            document.getElementById('updateProfileModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('updateProfileModal').classList.add('hidden');
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('updateProfileModal');
            if (e.target === modal) closeModal();
        });