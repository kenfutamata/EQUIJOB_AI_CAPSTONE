
function openJobDetailsModal(button) {
    const job = JSON.parse(button.dataset.jobposting);

    document.getElementById('modal-companyName').textContent = job.companyName || '';
    document.getElementById('modal-position').textContent = job.position || '';
    document.getElementById('modal-disabilityType').textContent = job.disabilityType || '';
    document.getElementById('modal-salaryRange').textContent = job.salaryRange || '';
    document.getElementById('modal-contactPhone').textContent = job.contactPhone || '';
    document.getElementById('modal-contactEmail').textContent = job.contactEmail || '';
    document.getElementById('modal-workEnvironment').textContent = job.workEnvironment || '';
    document.getElementById('modal-category').textContent = job.category || '';
    document.getElementById('modal-description').textContent = job.description || '';
    document.getElementById('modal-educationalAttainment').textContent = job.educationalAttainment || '';
    document.getElementById('modal-skills').textContent = job.skills || '';
    document.getElementById('modal-requirements').textContent = job.requirements || '';
    document.getElementById('modal-companyAddress').textContent = job.companyAddress || '';

    const logo = document.getElementById('modal-companyLogo');
    logo.src = job.companyLogo || '';
    logo.style.display = job.companyLogo ? 'block' : 'none';

    const modal = document.getElementById('viewJobDetailsModal');
    const modalContent = document.getElementById('modalContent');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('opacity-0', 'scale-95');
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