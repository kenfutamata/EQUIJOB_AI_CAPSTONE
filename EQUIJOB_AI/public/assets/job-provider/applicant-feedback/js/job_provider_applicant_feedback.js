function openviewDescriptionModal(button) {
    const feedback = JSON.parse(button.getAttribute('data-user'));
    const position = feedback.job_application?.job_posting?.position;
    const companyName = feedback.job_application?.job_posting?.companyName;
    document.getElementById('modal_firstName').value = feedback.firstName;
    document.getElementById('modal_lastName').value = feedback.lastName;
    document.getElementById('modal_email').value = feedback.email;
    document.getElementById('modal_position').value = position ?? 'N/A';
    document.getElementById('modal_companyName').value = companyName ?? 'N/A';
    document.getElementById('modal_phoneNumber').value = feedback.phoneNumber;
    document.getElementById('modal_feedbackType').value = feedback.feedbackType;
    document.getElementById('modal_feedbackText').value = feedback.feedbackText;
    document.getElementById('modal_rating').value = feedback.rating;
    document.getElementById('viewDescriptionModal').classList.remove('hidden');
}

function closeviewDescriptionModal() {
    document.getElementById('viewDescriptionModal').classList.add('hidden');
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