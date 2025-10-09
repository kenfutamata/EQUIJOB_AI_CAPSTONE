function openDisapproveJobPostingModal(button) {
    const jobposting = JSON.parse(button.getAttribute('data-jobposting'));
    const form = document.getElementById('disapproveForm');
    form.action = `/Admin/Manage-Job-Posting/Disapproved/${jobposting.id}`;
    document.getElementById('DisapproveJobPostingModal').classList.remove('hidden');
}

function closeDisapproveJobPostingModal() {
    document.getElementById('DisapproveJobPostingModal').classList.add('hidden');
}

function openAddJobPostingModal() {
    document.getElementById('addJobPostingModal').classList.remove('hidden');
}

function closeAddJobPostingModal() {
    document.getElementById('addJobPostingModal').classList.add('hidden');
}

function openDeleteModal(userId) {
    const form = document.getElementById('deleteuser');
    form.action = `/EQUIJOB/Admin/Manage-User-Applicants/Delete/${userId}`;
    document.getElementById('DeleteRoleModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('DeleteRoleModal').classList.add('hidden');
}

window.addEventListener('click', function(e) {
    const modal = document.getElementById('viewProfileModal');
    if (e.target === modal) closeModal();
});

setTimeout(() => {
    const notif = document.getElementById('notification-bar');
    if (notif) notif.style.opacity = '0';
}, 2500);
setTimeout(() => {
    const notif = document.getElementById('notification-bar');
    if (notif) notif.style.display = 'none';
}, 3000);

function openViewJobPostingModal(button) {
    const jobposting = JSON.parse(button.getAttribute('data-jobposting'));
    document.getElementById('modal.position').value = jobposting.position;
    document.getElementById('modal.companyName').value = jobposting.companyName;
    document.getElementById('modal.sex').value = jobposting.sex;
    const companyLogo = document.getElementById('modal.companyLogo');
  companyLogo.src = jobposting.companyLogo ? jobposting.companyLogo : '';
            companyLogo.style.display = jobposting.companyLogo ? 'block' : 'none';
    document.getElementById('modal.companyAddress').value = jobposting.companyAddress;
    document.getElementById('modal.age').value = jobposting.age;
    document.getElementById('modal.disabilityType').value = jobposting.disabilityType;
    document.getElementById('modal.educationalAttainment').value = jobposting.educationalAttainment;
    document.getElementById('modal.workEnvironment').value = jobposting.workEnvironment;
    document.getElementById('modal.jobPostingObjectives').value = jobposting.jobPostingObjectives;
    document.getElementById('modal.experience').value = jobposting.experience;
    document.getElementById('modal.skills').value = jobposting.skills;
    document.getElementById('modal.requirements').value = jobposting.requirements;
    document.getElementById('modal.contactPhone').value = jobposting.contactPhone;
    document.getElementById('modal.contactEmail').value = jobposting.contactEmail;
    document.getElementById('modal.description').value = jobposting.description;
    document.getElementById('modal.category').value = jobposting.category;
    document.getElementById('modal.salaryRange').value = jobposting.salaryRange;
    document.getElementById('modal.remarks').value = jobposting.remarks || '';
    document.getElementById('viewJobPostingModal').classList.remove('hidden');

}

function closeViewJobPostingModal() {
    document.getElementById('viewJobPostingModal').classList.add('hidden');
}