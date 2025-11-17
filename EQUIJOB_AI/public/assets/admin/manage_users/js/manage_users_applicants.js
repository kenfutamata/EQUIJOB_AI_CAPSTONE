function openProfileModal(button) {
    const user = JSON.parse(button.getAttribute('data-user'));
    document.getElementById('modal_firstName').value = user.firstName;
    document.getElementById('modal_lastName').value = user.lastName;
    document.getElementById('modal_email').value = user.email;
    document.getElementById('modal_address').value = user.address;
    document.getElementById('modal_province').value = user.province ? user.province.provinceName: 'N/a';
    document.getElementById('modal_city').value = user.city ? user.city.cityName: 'N/a';
    document.getElementById('modal_phoneNumber').value = user.phoneNumber;
    document.getElementById('modal_dateOfBirth').value = user.dateOfBirth;
    document.getElementById('modal_pwdId').value = user.pwdId;
    document.getElementById('typeOfDisability').value = user.typeOfDisability;
    const pwdCardImg = document.getElementById('modal_pwd_card');
    pwdCardImg.src = user.upload_pwd_card ? user.upload_pwd_card : '';
    pwdCardImg.style.display = user.upload_pwd_card ? 'block' : 'none';
    const profilePictureImg = document.getElementById('modal_profilePicture');
    profilePictureImg.src = user.profilePicture ? user.profilePicture : '';
    profilePictureImg.style.display = user.profilePicture ? 'block' : 'none';
    document.getElementById('modal_role').value = user.role;
    document.getElementById('modal_status').value = user.status;
    document.getElementById('viewProfileModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('viewProfileModal').classList.add('hidden');
}

function openDeleteModal(url) {
    const form = document.getElementById('deleteJobApplicantAccountForm');
    form.action = url;
    document.getElementById('DeleteRoleModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('DeleteRoleModal').classList.add('hidden');
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