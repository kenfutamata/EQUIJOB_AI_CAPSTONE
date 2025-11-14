function openViewJobPostingModal(button) {
    const job = JSON.parse(button.getAttribute('data-jobposting') || '{}');

    const setText = (id, value, fallback = 'N/A') => {
        const el = document.getElementById(id);
        if (el) el.textContent = value || fallback;
    };

    setText('modal-position', job.position);
    setText('modal-companyName', job.companyName);
    setText('modal-disabilityType', job.disabilityType, 'Not Specified');
    setText('modal-salaryRange', job.salaryRange, '₱ 0');
    setText('modal-educationalAttainment', job.educationalAttainment, 'No educational attainment provided.');
    setText('modal-workEnvironment', job.workEnvironment, 'No Work Environment provided.');
    setText('modal-skills', job.skills, 'No skills required.');
    setText('modal-description', job.description, 'No description provided.');
    setText('modal-requirements', job.requirements, 'No requirements provided.');
    setText('modal-contactPhone', job.contactPhone, 'No contact number provided.');
    setText('modal-contactEmail', job.contactEmail, 'No email address provided.');
    setText('modal-companyAddress', job.companyAddress);

    const logo = document.getElementById('modal-companyLogo');
    const initial = document.getElementById('modal-companyInitial');

    if (logo && initial) {
        if (job.companyLogo) {
            logo.src = job.companyLogo;
            logo.style.display = 'block';
            initial.style.display = 'none';
        } else {
            logo.style.display = 'none';
            initial.textContent = job.companyName ? job.companyName.charAt(0).toUpperCase() : '?';
            initial.style.display = 'flex';
        }
    }

    document.getElementById('viewJobPostingModal')?.classList.remove('hidden');
}

function closeViewJobPostingModal() {
    document.getElementById('viewJobPostingModal')?.classList.add('hidden');
}

function openApplyJobModal(button) {
    const jobposting = JSON.parse(button.getAttribute('data-jobposting') || '{}');

    const modal = document.getElementById('applyJobModal');
    if (!modal) return;

    document.getElementById('apply_jobPostingID').value = jobposting.id || '';
    document.getElementById('apply_jobProviderID').value = jobposting.jobProviderID || '';
    document.getElementById('apply_position').value = jobposting.position || '';
    document.getElementById('apply_companyName').value = jobposting.companyName || '';

    modal.classList.remove('hidden');
}

function closeApplyJobModal() {
    document.getElementById('applyJobModal')?.classList.add('hidden');
}

// Notification fade-out
setTimeout(() => {
    const notif = document.getElementById('notification-bar');
    if (notif) notif.style.opacity = '0';
}, 2500);

setTimeout(() => {
    const notif = document.getElementById('notification-bar');
    if (notif) notif.style.display = 'none';
}, 3000);

function openDeleteModal(button) {
    const form = document.getElementById('deleteJobPosting'); // corrected ID
    if (!form) return;

    form.action = button.getAttribute('data-action') || form.action;
    document.getElementById('DeleteJobPostingModal')?.classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('DeleteJobPostingModal')?.classList.add('hidden');
}
