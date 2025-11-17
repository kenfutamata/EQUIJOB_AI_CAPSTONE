const SUPABASE_BASE_URL = "https://zlusioxytbqhxohsfvyr.supabase.co/storage/v1/object/public/equijob_storage";

function openJobDetailsModal(button) {
    const jobposting = JSON.parse(button.getAttribute('data-jobposting'));

    document.getElementById('modal-position').textContent = jobposting.position || 'No Position provided';
    document.getElementById('modal-companyName').textContent = jobposting.companyName || 'No Company provided';
    document.getElementById('modal-disabilityType').textContent = jobposting.disabilityType || 'No Disability Type provided';
    document.getElementById('modal-educationalAttainment').textContent = jobposting.educationalAttainment || 'No Educational Attainment provided';
    document.getElementById('modal-workEnvironment').textContent = jobposting.workEnvironment || 'No Work Environment provided';
    document.getElementById('modal-skills').textContent = jobposting.skills || 'No Skills provided';
    document.getElementById('modal-requirements').textContent = jobposting.requirements || 'No Requirements provided';
    document.getElementById('modal-contactPhone').textContent = jobposting.contactPhone || 'No phone number provided';
    document.getElementById('modal-contactEmail').textContent = jobposting.contactEmail || 'No contact email provided';
    document.getElementById('modal-description').textContent = jobposting.description || 'No description provided';
    document.getElementById('modal-salaryRange').textContent = jobposting.salaryRange || 'No Salary Range provided';
    document.getElementById('modal-category').textContent = jobposting.category || 'No Category provided';
    document.getElementById('modal-companyAddress').textContent = jobposting.companyAddress || 'No Location provided';

    const logo = document.getElementById('modal-companyLogo');
    logo.src = jobposting.companyLogo || '';
    logo.style.display = jobposting.companyLogo ? 'block' : 'none';

    document.getElementById('viewJobDetailsModal').classList.remove('hidden');
}

function closeViewJobDetailsModal() {
    document.getElementById('viewJobDetailsModal').classList.add('hidden');
}