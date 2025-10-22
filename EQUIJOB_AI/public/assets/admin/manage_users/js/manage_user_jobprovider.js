const SUPABASE_BASE_URL = "https://zlusioxytbqhxohsfvyr.supabase.co/storage/v1/object/public/equijob_storage";

window.openProfileModal = function (button) {
    const user = JSON.parse(button.getAttribute('data-user'));
    document.getElementById('modal_firstName').value = user.firstName;
    document.getElementById('modal_lastName').value = user.lastName;
    document.getElementById('modal_email').value = user.email;
    document.getElementById('modal_phoneNumber').value = user.phoneNumber;
    document.getElementById('modal_companyName').value = user.companyName;

    const permitContainer = document.getElementById('modal_businessPermit_container');
    permitContainer.innerHTML = '';

    if (user.businessPermit) {
        const filePath = user.businessPermit.startsWith('http') ? user.businessPermit : `${SUPABASE_BASE_URL}/businessPermit/${user.businessPermit}`;
        const fileExtension = filePath.split('.').pop().toLowerCase();
        if (['jpg', 'jpeg', 'png', 'webp'].includes(fileExtension)) {
            permitContainer.innerHTML = `<img src="${filePath}" class="w-[100px] h-[100px] object-cover" />`;
        } else if (fileExtension === 'pdf') {
            permitContainer.innerHTML = `<a href="${filePath}" target="_blank" class="text-blue-500 underline">View Business Permit (PDF)</a>`;
        } else {
            permitContainer.innerText = 'Unsupported file format';
        }
    }

    const profilePicture = document.getElementById('modal_profilePicture');
    profilePicture.src = user.profilePicture ? user.profilePicture : '';
    profilePicture.style.display = user.profilePicture ? 'block' : 'none';

    const logo = document.getElementById('modal_companyLogo');
    logo.src = user.companyLogo ? user.companyLogo : '';
    logo.style.display = user.companyLogo ? 'block' : 'none';

    document.getElementById('modal_role').value = user.role;
    document.getElementById('modal_status').value = user.status;

    document.getElementById('viewProfileModal').classList.remove('hidden');
};

window.closeModal = function () {
    document.getElementById('viewProfileModal').classList.add('hidden');
};

window.openDeleteModal = function (url) {
    const form = document.getElementById('deleteJobProviderAccountForm');
    form.action = url;
    document.getElementById('DeleteRoleModal').classList.remove('hidden');
};

window.closeDeleteModal = function () {
    document.getElementById('DeleteRoleModal').classList.add('hidden');
};

setTimeout(() => {
    const notif = document.getElementById('notification-bar');
    if (notif) notif.style.opacity = '0';
}, 2500);

setTimeout(() => {
    const notif = document.getElementById('notification-bar');
    if (notif) notif.style.display = 'none';
}, 3000);
