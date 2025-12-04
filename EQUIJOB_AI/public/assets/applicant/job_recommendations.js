function showNotification(message, type = 'error') {
    const container = document.getElementById('notification-container');
    const content = document.getElementById('notification-content');
    const icon = document.getElementById('notification-icon');
    const msgText = document.getElementById('notification-message');
    
    if (type === 'error') {
        content.className = "shadow-lg rounded-lg p-4 flex items-center gap-3 text-white bg-red-500";
        icon.innerHTML = `<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;
    } else {
        content.className = "shadow-lg rounded-lg p-4 flex items-center gap-3 text-white bg-green-500";
        icon.innerHTML = `<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>`;
    }

    msgText.textContent = message;

    // Reset Animation
    container.classList.remove('hidden', 'fade-out-up');
    container.classList.add('fade-in-down');

    // Hide after 3.5 seconds
    setTimeout(() => {
        container.classList.remove('fade-in-down');
        container.classList.add('fade-out-up');
        setTimeout(() => {
            container.classList.add('hidden');
        }, 300);
    }, 3500);
}

function openApplyJobModal(button) {
    const jobposting = JSON.parse(button.getAttribute('data-jobposting') || '{}');
    const id = parseInt(jobposting.id); // Force the ID to be an Integer

    const numericAppliedIds = appliedJobIds.map(id => parseInt(id));

    if (numericAppliedIds.includes(id)) {
        showNotification('You have already submitted an application for this position.', 'error');
        return; 
    }

    const modal = document.getElementById('applyJobModal');
    
    // Set form values
    document.getElementById('apply_jobPostingID').value = jobposting.id || '';
    document.getElementById('apply_jobProviderID').value = jobposting.jobProviderID || '';
    document.getElementById('apply_position').value = jobposting.position || '';
    document.getElementById('apply_companyName').value = jobposting.companyName || '';
    
    modal.classList.remove('hidden');
}

function closeApplyJobModal() {
    document.getElementById('applyJobModal').classList.add('hidden');
}

// --- VIEW MODAL LOGIC ---
function openViewJobPostingModal(button) {
    const job = JSON.parse(button.getAttribute('data-jobposting') || '{}');
    const setText = (id, val) => { 
        const el = document.getElementById(id); 
        if(el) el.textContent = val || 'Not Specified'; 
    };

    setText('modal-position', job.position);
    setText('modal-companyName', job.companyName);
    setText('modal-disabilityType', job.disabilityType);
    setText('modal-salaryRange', job.salaryRange);
    setText('modal-educationalAttainment', job.educationalAttainment);
    setText('modal-workEnvironment', job.workEnvironment);
    setText('modal-skills', job.skills);
    setText('modal-description', job.description);
    setText('modal-requirements', job.requirements);
    setText('modal-contactPhone', job.contactPhone);
    setText('modal-contactEmail', job.contactEmail);
    setText('modal-category', job.category);
    setText('modal-companyAddress', job.companyAddress);

    const logo = document.getElementById('modal-companyLogo');
    const initial = document.getElementById('modal-companyInitial');

    if (job.companyLogo) {
        logo.src = job.companyLogo;
        logo.style.display = 'block';
        initial.style.display = 'none';
    } else {
        logo.style.display = 'none';
        initial.textContent = job.companyName ? job.companyName.charAt(0).toUpperCase() : '?';
        initial.style.display = 'flex';
    }

    document.getElementById('viewJobPostingModal').classList.remove('hidden');
}

function closeViewJobPostingModal() {
    document.getElementById('viewJobPostingModal').classList.add('hidden');
}
