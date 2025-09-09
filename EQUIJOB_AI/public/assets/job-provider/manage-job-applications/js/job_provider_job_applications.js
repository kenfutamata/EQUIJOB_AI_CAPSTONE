document.addEventListener('DOMContentLoaded', function () {
    // --- MODAL CONTROL FUNCTIONS ---

    // View Application Modal
    window.openViewJobApplicationsModal = function (button) {
        const modal = document.getElementById('viewJobApplicationsModal');
        const data = JSON.parse(button.getAttribute('data-application'));

        document.getElementById('modal-applicantName').textContent = `${data.firstName} ${data.lastName}`;
        document.getElementById('modal-position').textContent = data.position;
        document.getElementById('modal-companyName').textContent = data.companyName;
        document.getElementById('modal-disabilityType').textContent = data.disabilityType || 'N/A';
        document.getElementById('modal-sex').textContent = data.sex || 'N/A';
        document.getElementById('modal-contactPhone').textContent = data.contactPhone || 'N/A';
        document.getElementById('modal-contactEmail').textContent = data.contactEmail || 'N/A';

        document.getElementById('modal-interviewDate').textContent = data.interviewDate || 'Not Scheduled';
        document.getElementById('modal-interviewTime').textContent = data.interviewTime || 'Not Scheduled';
        document.getElementById('modal-interviewLink').innerHTML = data.interviewLink ? `<a href="${data.interviewLink}" target="_blank" class="text-blue-600 hover:underline">Join Meeting</a>` : 'No link provided';
        document.getElementById('modal-remarks').textContent = data.remarks || 'No remarks.';

        // Handle profile picture
        const profileImg = document.getElementById('modal-applicantProfile');
        if (data.profile_picture) {
            profileImg.src = `/storage/${data.profile_picture}`;
        } else {
            profileImg.src = `https://ui-avatars.com/api/?name=${data.firstName}+${data.lastName}&background=random`;
        }

        // Handle resume and application letter links
        const resumeContainer = document.getElementById('modal_view_resume');
        resumeContainer.innerHTML = data.uploadResume ? `<a href="/storage/${data.uploadResume}" target="_blank" class="text-blue-600 hover:underline">View Resume</a>` : '<span>Not provided</span>';

        const letterContainer = document.getElementById('modal_view_application_letter');
        letterContainer.innerHTML = data.uploadApplicationLetter ? `<a href="/storage/${data.uploadApplicationLetter}" target="_blank" class="text-blue-600 hover:underline">View Letter</a>` : '<span>Not provided</span>';

        modal.classList.remove('hidden');
    };

    window.closeViewJobApplicationsModal = function () {
        document.getElementById('viewJobApplicationsModal').classList.add('hidden');
    };

    window.openCreateInterviewDetailsModal = function (formActionUrl) {
        const modal = document.getElementById('createInterviewDetailsModal');
        const form = document.getElementById('interviewForm');
        form.action = formActionUrl;
        modal.classList.remove('hidden');
    };

    window.closeCreateInterviewDetailsModal = function () {
        const modal = document.getElementById('createInterviewDetailsModal');
        const form = document.getElementById('interviewForm');
        form.reset();
        document.getElementById('create_modal_meet_link').value = '';
        document.getElementById('interviewLink').value = '';
        document.getElementById('submitInterviewBtn').disabled = true;
        modal.classList.add('hidden');
    };

    // Reject Application Modal
    window.openRejectJobApplicationModal = function (button) {
        const modal = document.getElementById('rejectJobApplicationModal');
        const form = document.getElementById('rejectForm');
        form.action = button.dataset.url;
        modal.classList.remove('hidden');
    };

    window.closeRejectJobApplicationModal = function () {
        document.getElementById('rejectJobApplicationModal').classList.add('hidden');
    };

    window.openDeleteApplicationModal = function (url){
        const form = document.getElementById('deleteApplication');
        form.action = url;
        document.getElementById('DeleteApplicationModal').classList.remove('hidden');
    }

    window.closeDeleteApplicationModal = function () {
        document.getElementById('DeleteApplicationModal').classList.add('hidden');
    };



    const generateBtn = document.getElementById('generate-meet-link-btn');
    const visibleLinkInput = document.getElementById('create_modal_meet_link');
    const hiddenLinkInput = document.getElementById('interviewLink');
    const submitInterviewBtn = document.getElementById('submitInterviewBtn');

    if (generateBtn) {
        generateBtn.addEventListener('click', function () {
            const url = this.dataset.url;

            this.disabled = true;
            this.textContent = '...';
            visibleLinkInput.value = '';
            visibleLinkInput.placeholder = 'Generating link, please wait...';
            submitInterviewBtn.disabled = true;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok. Status: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.meetLink) {

                        visibleLinkInput.value = data.meetLink;
                        hiddenLinkInput.value = data.meetLink;

                        submitInterviewBtn.disabled = false;
                    } else if (data.error) {
                        alert('Error: ' + data.error);
                        visibleLinkInput.placeholder = 'Failed to generate link.';
                    }
                })
                .catch(error => {
                    console.error('Error generating Meet link:', error);
                    alert('An API or network error occurred. Check the console and your Laravel log for details.');
                    visibleLinkInput.placeholder = 'Error. Please try again.';
                })
                .finally(() => {
                    this.disabled = false;
                    this.textContent = 'Generate';
                });
        });
    }


    setTimeout(() => {
        const notif = document.getElementById('notification-bar');
        if (notif) {
            notif.style.opacity = '0';
            setTimeout(() => notif.style.display = 'none', 500);
        }
    }, 5000);
});