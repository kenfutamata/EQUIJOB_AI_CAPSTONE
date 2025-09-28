        function openJobDetailsModal(button) {
            const jobposting = JSON.parse(button.getAttribute('data-jobposting'));
            document.getElementById('modal-position').textContent = jobposting.position || 'N/A';
            document.getElementById('modal-companyName').textContent = jobposting.companyName || 'N/A';
            document.getElementById('modal-disabilityType').textContent = jobposting.disabilityType || 'N/A';
            document.getElementById('modal-educationalAttainment').textContent = jobposting.educationalAttainment || 'N/A';
            document.getElementById('modal-workEnvironment').textContent = jobposting.workEnvironment || 'N/A';
            document.getElementById('modal-skills').textContent = jobposting.skills || 'N/A';
            document.getElementById('modal-requirements').textContent = jobposting.requirements || 'N/A';
            document.getElementById('modal-contactPhone').textContent = jobposting.contactPhone || 'N/A';
            document.getElementById('modal-contactEmail').textContent = jobposting.contactEmail || 'N/A';
            document.getElementById('modal-description').textContent = jobposting.description || 'N/A';
            document.getElementById('modal-salaryRange').textContent = jobposting.salaryRange || 'N/A';
            document.getElementById('modal-category').textContent = jobposting.category || 'N/A';

            const companyLogo = document.getElementById('modal-companyLogo');
            if (jobposting.companyLogo) {
                companyLogo.src = `/storage/${jobposting.companyLogo}`;
                companyLogo.style.display = 'block';
            } else {
                companyLogo.style.display = 'none';
            }

            const modal = document.getElementById('viewJobDetailsModal');
            const modalContent = document.getElementById('modalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        function closeViewJobDetailsModal() {
            const modal = document.getElementById('viewJobDetailsModal');
            const modalContent = document.getElementById('modalContent');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") {
                closeViewJobDetailsModal();
            }
        });