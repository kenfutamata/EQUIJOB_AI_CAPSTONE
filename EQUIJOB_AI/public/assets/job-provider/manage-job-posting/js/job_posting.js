        function openAddJobPostingModal() {
            document.getElementById('addJobPostingModal').classList.remove('hidden');
        }

        function closeAddJobPostingModal() {
            document.getElementById('addJobPostingModal').classList.add('hidden');
        }

        function openDeleteModal(button) {
            const form = document.getElementById('deletejobpositng');
            form.action = button.getAttribute('data-action');
            document.getElementById('DeleteJobPostingModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('DeleteJobPostingModal').classList.add('hidden');
        }

        setTimeout(() => {
            const notif = document.getElementById('notification-bar');
            if (notif) notif.style.opacity = '10';
        }, 2500);
        setTimeout(() => {
            const notif = document.getElementById('notification-bar');
            if (notif) notif.style.display = 'none';
        }, 3000);

        function openViewJobPostingModal(button) {
            const jobposting = JSON.parse(button.getAttribute('data-jobposting'));
            const addressParts =[
                jobposting.companyAddress, 
                jobposting.cityName, 
                jobposting.provinceName
            ].filter(Boolean); 
            
            document.getElementById('modal.position').value = jobposting.position || 'No Position provided';
            document.getElementById('modal.companyName').value = jobposting.companyName || 'No Company Name provided';
            document.getElementById('modal.companyAddress').value = addressParts.length ? addressParts.join(', '): 'No Address provided';
            document.getElementById('modal.sex').value = jobposting.sex || 'No Sex provided';
            document.getElementById('modal.age').value = jobposting.age || 'No Age provided';
            document.getElementById('modal.disabilityType').value = jobposting.disabilityType || 'No Disability Type provided';
            document.getElementById('modal.educationalAttainment').value = jobposting.educationalAttainment || 'No Educational Attainment provided';
            document.getElementById('modal.workEnvironment').value = jobposting.workEnvironment || 'No Work Environment provided';
            document.getElementById('modal.jobPostingObjectives').value = jobposting.jobPostingObjectives || 'No Objectives provided';
            document.getElementById('modal.experience').value = jobposting.experience || 'No Experience provided';
            document.getElementById('modal.skills').value = jobposting.skills || 'No Skills provided';
            document.getElementById('modal.requirements').value = jobposting.requirements || 'No Requirements provided';
            document.getElementById('modal.contactPhone').value = jobposting.contactPhone || 'No phone number provided';
            document.getElementById('modal.contactEmail').value = jobposting.contactEmail || 'No contact email provided';
            document.getElementById('modal.description').value = jobposting.description || 'No description provided';
            document.getElementById('modal.salaryRange').value = jobposting.salaryRange || 'No Salary Range provided';
            document.getElementById('modal.remarks').value = jobposting.remarks || 'N/A';
            const companyLogo = document.getElementById('modal.companyLogo');
            companyLogo.src = jobposting.companyLogo ? jobposting.companyLogo : '';
            companyLogo.style.display = jobposting.companyLogo ? 'block' : 'none';
            document.getElementById('viewJobPostingModal').classList.remove('hidden');
        }

        function closeViewJobPostingModal() {
            document.getElementById('viewJobPostingModal').classList.add('hidden');
        }

        document.getElementById('companyLogoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            document.getElementById('companyLogoFilename').textContent = file ? file.name : '';
        });