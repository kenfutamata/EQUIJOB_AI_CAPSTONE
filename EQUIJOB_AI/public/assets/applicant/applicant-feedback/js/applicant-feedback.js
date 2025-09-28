function openviewDescriptionModal(button) {
    const feedback = JSON.parse(button.getAttribute('data-user'));
    const position = feedback.job_posting?.position;
    const companyName = feedback.job_posting?.companyName;
    document.getElementById('modal_firstName').value = feedback.firstName || 'N/A';
    document.getElementById('modal_lastName').value = feedback.lastName || 'N/A';
    document.getElementById('modal_email').value = feedback.email || 'N/A';
    document.getElementById('modal_phoneNumber').value = feedback.phoneNumber || 'N/A';
    document.getElementById('modal_feedbackType').value = feedback.feedbackType || 'N/A';
    document.getElementById('modal_position').value = position || 'N/A';
    document.getElementById('modal_companyName').value = companyName || 'N/A';
    document.getElementById('modal_feedbackText').value = feedback.feedbackText || 'No feedback provided yet.';
    document.getElementById('modal_rating').value = feedback.rating || 'Not rated yet.';
    document.getElementById('viewDescriptionModal').classList.remove('hidden');
}

function closeviewDescriptionModal() {
    document.getElementById('viewDescriptionModal').classList.add('hidden');
}

function openFeedbackSubmitModal(feedback) {
    const modal = document.getElementById('submitFeedbackDetails');
    const form = document.getElementById('submitFeedback');
    const url = `{{ url('/Applicant/Applicant-Feedback') }}/${feedback.id}`;
    form.action = url;
    modal.classList.remove('hidden');
}

function closeFeedbackSubmitModal() {
    document.getElementById('submitFeedbackDetails').classList.add('hidden');
}

// Auto-hide notification bar
setTimeout(() => {
    const notif = document.getElementById('notification-bar');
    if (notif) {
        notif.style.opacity = '0';
        setTimeout(() => notif.style.display = 'none', 500);
    }
}, 3000);