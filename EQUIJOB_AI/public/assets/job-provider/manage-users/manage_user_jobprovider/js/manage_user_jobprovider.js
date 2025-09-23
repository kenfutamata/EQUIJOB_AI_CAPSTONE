function openProfileModal(button) {
    const user = JSON.parse(button.getAttribute('data-user'));
    document.getElementById('modal_firstName').value = user.firstName;
    document.getElementById('modal_lastName').value = user.lastName;
    document.getElementById('modal_email').value = user.email;
    document.getElementById('modal_phoneNumber').value = user.phoneNumber;
    document.getElementById('modal_companyName').value = user.companyName;
    const permitContainer = document.getElementById('modal_businessPermit_container');
    permitContainer.innerHTML = '';

    if (user.businessPermit) {
        const fileExtension = user.businessPermit.split('.').pop().toLowerCase();
        const filePath = `/storage/${user.businessPermit}`;

        if (['jpg', 'jpeg', 'png', 'webp'].includes(fileExtension)) {
            permitContainer.innerHTML = `<img src="${filePath}" class="w-[100px] h-[100px] object-cover" />`;
        } else if (fileExtension === 'pdf') {
            permitContainer.innerHTML = `<a href="${filePath}" target="_blank" class="text-blue-500 underline">View Business Permit (PDF)</a>`;
        } else {
            permitContainer.innerText = 'Unsupported file format';
        }
    }
    const profilePicture = document.getElementById('modal_profilePicture');
    if(user.profilePicture){
        profilePicture.src = `/storage/${user.profilePicture}`;
        profilePicture.style.display = 'block';
    }else{
        profilePicture.style.display = 'none';
    }
    document.getElementById('modal_role').value = user.role;
    document.getElementById('modal_status').value = user.status;

    const logo = document.getElementById('modal_companyLogo');
    if (user.companyLogo) {
        logo.src = `/storage/${user.companyLogo}`;
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