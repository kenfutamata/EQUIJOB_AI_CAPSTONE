function openViewJobPostingModal(button) {
    const job = JSON.parse(button.getAttribute('data-jobposting'));

    document.getElementById('modal-position').textContent = job.position || 'N/A';
    document.getElementById('modal-companyName').textContent = job.companyName || 'N/A';
    document.getElementById('modal-disabilityType').textContent = job.disabilityType || 'Not Specified';
    document.getElementById('modal-salaryRange').textContent = job.salaryRange || '₱ 0';
    document.getElementById('modal-educationalAttainment').textContent = job.educationalAttainment || 'No educational attainment provided.';
    document.getElementById('modal-workEnvironment').textContent = job.workEnvironment || 'No Work Envionment provided.';

    document.getElementById('modal-skills').textContent = job.skills || 'No skills required.';
    document.getElementById('modal-description').textContent = job.description || 'No description provided.';
    document.getElementById('modal-requirements').textContent = job.requirements || 'No requirements provided.';
    document.getElementById('modal-contactPhone').textContent = job.contactPhone || 'No contact number provided.';
    document.getElementById('modal-contactEmail').textContent = job.contactEmail || 'No email address provided.';

    const logo = document.getElementById('modal-companyLogo');
    const initial = document.getElementById('modal-companyInitial');
    if (job.companyLogo) {
        logo.src = `/storage/${job.companyLogo}`;
        logo.style.display = 'block';
        initial.style.display = 'none';
    } else {
        logo.style.display = 'none';
        initial.textContent = job.companyName ? job.companyName.charAt(0) : '';
        initial.style.display = 'flex';
    }

    document.getElementById('viewJobPostingModal').classList.remove('hidden');
}

function closeViewJobPostingModal() {
    document.getElementById('viewJobPostingModal').classList.add('hidden');
}

function openApplyJobModal(button) {
    const jobposting = JSON.parse(button.getAttribute('data-jobposting'));
    const modal = document.getElementById('applyJobModal');

    document.getElementById('apply_jobPostingID').value = jobposting.id;
    document.getElementById('apply_jobProviderID').value = jobposting.jobProviderID;

    document.getElementById('apply_position').value = jobposting.position;
    document.getElementById('apply_companyName').value = jobposting.companyName;

    modal.classList.remove('hidden');
}

function closeApplyJobModal() {
    document.getElementById('applyJobModal').classList.add('hidden');
}

setTimeout(() => {
    const notif = document.getElementById('notification-bar');
    if (notif) notif.style.opacity = '0';
}, 2500);
setTimeout(() => {
    const notif = document.getElementById('notification-bar');
    if (notif) notif.style.display = 'none';
}, 3000);

function openDeleteModal(button) {
    const form = document.getElementById('deletejobpositng');
    form.action = button.getAttribute('data-action');
    document.getElementById('DeleteJobPostingModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('DeleteJobPostingModal').classList.add('hidden');
}