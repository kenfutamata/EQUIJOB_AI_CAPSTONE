function getStorageUrl(path, folder) {
    if (!path) {
        return null;
    }
    // If it's already a full URL, return it
    if (path.startsWith('http')) {
        return path;
    }
    // Otherwise, build the Supabase URL
    const supabaseBaseUrl = "https://zlusioxytbqhxohsfvyr.supabase.co/storage/v1/object/public/equijob_storage";
    return `${supabaseBaseUrl}/${folder}/${path}`;
}

function openProfileModal(button) {
    const user = JSON.parse(button.getAttribute('data-user'));

    // Populate standard text fields
    document.getElementById('modal_firstName').value = user.firstName || 'N/A';
    document.getElementById('modal_lastName').value = user.lastName || 'N/A';
    document.getElementById('modal_email').value = user.email || 'N/A';
    document.getElementById('modal_phoneNumber').value = user.phoneNumber || 'N/A';
    document.getElementById('modal_companyName').value = user.companyName || 'N/A';
    document.getElementById('modal_companyAddress').value = user.companyAddress || 'N/A';
    document.getElementById('modal_province').value = user.province || 'N/A';
    document.getElementById('modal_city').value = user.city || 'N/A';
    document.getElementById('modal_role').value = user.role || 'N/A';
    document.getElementById('modal_status').value = user.status || 'N/A';

    // Handle Company Logo
    const logoImg = document.getElementById('modal_companyLogo');
    const logoUrl = getStorageUrl(user.companyLogo, 'companyLogo');
    if (logoUrl) {
        logoImg.src = logoUrl;
        logoImg.style.display = 'block';
    } else {
        logoImg.style.display = 'none';
    }

    // Handle Profile Picture
    const profileImg = document.getElementById('modal_profilePicture');
    const profileUrl = getStorageUrl(user.profilePicture, 'profilePicture');
    if (profileUrl) {
        profileImg.src = profileUrl;
        profileImg.style.display = 'block';
    } else {
        profileImg.style.display = 'none';
    }

    // Handle Business Permit (Image or PDF link)
    const permitContainer = document.getElementById('modal_businessPermit_container');
    permitContainer.innerHTML = ''; // Clear previous content
    const permitUrl = getStorageUrl(user.businessPermit, 'businessPermit');
    if (permitUrl) {
        const fileExtension = user.businessPermit.split('.').pop().toLowerCase();
        if (['jpg', 'jpeg', 'png', 'webp', 'gif'].includes(fileExtension)) {
            permitContainer.innerHTML = `<img src="${permitUrl}" class="w-[100px] h-[100px] object-cover" alt="Business Permit" />`;
        } else if (fileExtension === 'pdf') {
            permitContainer.innerHTML = `<a href="${permitUrl}" target="_blank" class="text-blue-500 underline">View Business Permit (PDF)</a>`;
        } else {
            permitContainer.innerHTML = `<a href="${permitUrl}" target="_blank" class="text-blue-500 underline">Download Business Permit</a>`;
        }
    } else {
        permitContainer.innerText = 'No permit provided.';
    }

    // Show the modal
    document.getElementById('viewProfileModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('viewProfileModal').classList.add('hidden');
}

function openDeleteModal(url) {
    const form = document.getElementById('deleteJobProviderAccount');
    form.action =url;
    document.getElementById('DeleteRoleModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('DeleteRoleModal').classList.add('hidden');
}

// Notification bar auto-hide logic
setTimeout(() => {
    const notif = document.getElementById('notification-bar');
    if (notif) {
        notif.style.transition = 'opacity 0.5s ease-out';
        notif.style.opacity = '0';
        setTimeout(() => {
            notif.style.display = 'none';
        }, 500); // Wait for transition to finish
    }
}, 2500);