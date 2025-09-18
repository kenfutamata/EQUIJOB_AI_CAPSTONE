document.addEventListener('DOMContentLoaded', function () {
    const notificationBar = document.getElementById('notification-bar');
    if (notificationBar) {
        setTimeout(() => {
            notificationBar.style.opacity = '0';
            notificationBar.style.transition = 'opacity 0.5s ease-out';
        }, 2500);
        setTimeout(() => {
            notificationBar.style.display = 'none';
        }, 3000);
    }

    window.addEventListener('click', function (e) {
        const viewModal = document.getElementById('viewJobApplicationModal');
        const withdrawModal = document.getElementById('withdrawJobApplicationModal');
        if (e.target === viewModal) {
            closeviewJobApplicationModal();
        }
        if (e.target === withdrawModal) {
            closeWithdrawModal();
        }
    });
});



function openViewJobApplicationModal(button) {
    const applicationData = JSON.parse(button.getAttribute('data-application'));

    const fullName = `${applicationData.firstName || ''} ${applicationData.lastName || ''}`.trim();
    const profilePictureImg = document.getElementById('modal.applicantProfile');
    if (applicationData.profile_picture) {
        profilePictureImg.src = `/storage/${applicationData.profile_picture}`;
        profilePictureImg.style.display = 'block';
        document.getElementById('modal.applicantInitial').style.display = 'none';
    } else {
        profilePictureImg.src = `/assets/applicant/applicant-dashboard/profile_pic.png`;
        profilePictureImg.style.display = 'block';
        document.getElementById('modal.applicantInitial').style.display = 'none';
    }

    document.getElementById('modal.applicantName').textContent = fullName || 'N/A';
    document.getElementById('modal.position').textContent = applicationData.position ?? 'N/A';
    document.getElementById('modal.companyName').textContent = applicationData.companyName ?? 'N/A';
    document.getElementById('modal.remarks').textContent = applicationData.remarks ?? 'N/A';
    document.getElementById('modal.interviewDate').textContent = applicationData.interviewDate ?? 'N/A';
    document.getElementById('modal.interviewTime').textContent = applicationData.interviewTime ?? 'N/A';
    document.getElementById('modal.interviewLink').textContent = applicationData.interviewLink ?? 'N/A';
    document.getElementById('modal.disabilityType').textContent = applicationData.disabilityType ?? 'N/A';
    document.getElementById('modal.sex').textContent = applicationData.gender ?? 'N/A';
    document.getElementById('modal.contactPhone').textContent = applicationData.contactPhone ?? 'N/A';
    document.getElementById('modal.contactEmail').textContent = applicationData.contactEmail ?? 'N/A';

    const resumeContainer = document.getElementById('modal_view_resume');
    resumeContainer.innerHTML = '';
    if (applicationData.uploadResume) {
        const filePath = `/storage/${applicationData.uploadResume}`;
        const ext = applicationData.uploadResume.split('.').pop().toLowerCase();
        if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
            resumeContainer.innerHTML = `<a href="${filePath}" target="_blank"><img src="${filePath}" class="w-[100px] h-[100px] object-cover" alt="Resume Preview"/></a>`;
        } else if (ext === 'pdf') {
            resumeContainer.innerHTML = `<a href="${filePath}" target="_blank" class="text-blue-500 underline">View Resume (PDF)</a>`;
        } else {
            resumeContainer.innerHTML = `<a href="${filePath}" target="_blank" class="text-blue-500 underline">Download Resume</a>`;
        }
    } else {
        resumeContainer.innerText = 'No resume uploaded.';
    }

    const applicationContainer = document.getElementById('modal_view_application_letter');
    applicationContainer.innerHTML = '';
    if (applicationData.uploadApplicationLetter) {
        const filePath = `/storage/${applicationData.uploadApplicationLetter}`;
        const ext = applicationData.uploadApplicationLetter.split('.').pop().toLowerCase();
        if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
            applicationContainer.innerHTML = `<a href="${filePath}" target="_blank"><img src="${filePath}" class="w-[100px] h-[100px] object-cover" alt="Application Letter Preview"/></a>`;
        } else if (ext === 'pdf') {
            applicationContainer.innerHTML = `<a href="${filePath}" target="_blank" class="text-blue-500 underline">View Application Letter (PDF)</a>`;
        } else {
            applicationContainer.innerHTML = `<a href="${filePath}" target="_blank" class="text-blue-500 underline">Download Application Letter</a>`;
        }
    } else {
        applicationContainer.innerText = 'No Application Letter uploaded.';
    }

    document.getElementById('viewJobApplicationModal').classList.remove('hidden');
}

function closeviewJobApplicationModal() {
    document.getElementById('viewJobApplicationModal').classList.add('hidden');
}

function openWithdrawModal(button) {
    const formActionUrl = button.getAttribute('data-url');
    const form = document.getElementById('withdrawForm');
    form.action = formActionUrl;
    document.getElementById('withdrawJobApplicationModal').classList.remove('hidden');
}

function closeWithdrawModal() {
    document.getElementById('withdrawJobApplicationModal').classList.add('hidden');
}