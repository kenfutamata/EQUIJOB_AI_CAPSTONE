function openProfileModal(button) {
    const user = JSON.parse(button.getAttribute('data-user'));
    document.getElementById('modal_first_name').value = user.first_name;
    document.getElementById('modal_last_name').value = user.last_name;
    document.getElementById('modal_email').value = user.email;
    document.getElementById('modal_phone_number').value = user.phone_number;
    document.getElementById('modal_company_name').value = user.company_name;
    const permitContainer = document.getElementById('modal_business_permit_container');
    permitContainer.innerHTML = '';

    if (user.business_permit) {
        const fileExtension = user.business_permit.split('.').pop().toLowerCase();
        const filePath = `/storage/${user.business_permit}`;

        if (['jpg', 'jpeg', 'png', 'webp'].includes(fileExtension)) {
            permitContainer.innerHTML = `<img src="${filePath}" class="w-[100px] h-[100px] object-cover" />`;
        } else if (fileExtension === 'pdf') {
            permitContainer.innerHTML = `<a href="${filePath}" target="_blank" class="text-blue-500 underline">View Business Permit (PDF)</a>`;
        } else {
            permitContainer.innerText = 'Unsupported file format';
        }
    }
    const profilePicture = document.getElementById('modal_profile_picture');
    if(user.profile_picture){
        profilePicture.src = `/storage/${user.profile_picture}`;
        profilePicture.style.display = 'block';
    }else{
        profilePicture.style.display = 'none';
    }
    document.getElementById('modal_role').value = user.role;
    document.getElementById('modal_status').value = user.status;

    const logo = document.getElementById('modal_company_logo');
    if (user.company_logo) {
        logo.src = `/storage/${user.company_logo}`;
        logo.style.display = 'block';
    } else {
        logo.style.display = 'none';
    }

    document.getElementById('viewProfileModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('viewProfileModal').classList.add('hidden');
}

function openDeleteModal(url) {
    const form = document.getElementById('deleteuser');
    form.action = url;
    document.getElementById('DeleteRoleModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('DeleteRoleModal').classList.add('hidden');
}

setTimeout(() => {
    const notif = document.getElementById('notification-bar');
    if (notif) notif.style.opacity = '0';
}, 2500);
setTimeout(() => {
    const notif = document.getElementById('notification-bar');
    if (notif) notif.style.display = 'none';
}, 3000);