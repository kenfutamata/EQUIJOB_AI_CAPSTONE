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
});