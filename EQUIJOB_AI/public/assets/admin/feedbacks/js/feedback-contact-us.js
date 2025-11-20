function openviewDescriptionModal(button) {
    const feedback = JSON.parse(button.getAttribute('data-user'));
    const fullTimestamp = feedback.updated_at;

    document.getElementById('modal_firstName').value = feedback.firstName;
    document.getElementById('modal_lastName').value = feedback.lastName;
    document.getElementById('modal_email').value = feedback.email;
    document.getElementById('modal_phoneNumber').value = feedback.phoneNumber;
    document.getElementById('modal_feedbackType').value = feedback.feedbackType;
    document.getElementById('modal_feedbackText').value = feedback.feedbackText;
    if (fullTimestamp) {
        const dateOnly = new Date(fullTimestamp).toISOString().split('T')[0];

        document.getElementById('modal_updated_at').value = dateOnly;
    }
    document.getElementById('viewDescriptionModal').classList.remove('hidden');
}

function closeviewDescriptionModal() {
    document.getElementById('viewDescriptionModal').classList.add('hidden');
}

function openDeleteFeedbackForm(deleteURL) {
    const form = document.getElementById('deleteFeedback');
    form.action = deleteURL;
    document.getElementById('deleteFeedbackForm').classList.remove('hidden');
}

function closeDeleteFeedbackForm() {
    document.getElementById('deleteFeedbackForm').classList.add('hidden');
}

window.addEventListener('click', function (e) {
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