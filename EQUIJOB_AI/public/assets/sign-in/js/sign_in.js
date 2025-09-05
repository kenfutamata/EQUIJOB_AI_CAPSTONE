document.querySelector('a[href="#"]').addEventListener('click', function(e) {
  if (this.textContent.trim() === 'Sign up') {
      e.preventDefault();
      document.getElementById('roleModal').classList.remove('hidden');
  }
});

function closeModal() {
  document.getElementById('roleModal').classList.add('hidden');
}

window.addEventListener('click', function(e) {
  const modal = document.getElementById('roleModal');
  if (e.target === modal) closeModal();
});

function togglePassword() {
  const passwordInput = document.getElementById('password');
  const icon = document.getElementById('eyeIcon');
  const isHidden = passwordInput.type === 'password';
  passwordInput.type = isHidden ? 'text' : 'password';
  const span = icon.nextElementSibling;
  if (isHidden) {
      span.textContent = "Show";
  } else {
      span.textContent = "Hide";
  }
}

setTimeout(() => {
  const notif = document.getElementById('notification-bar');
  if (notif) {
    notif.classList.remove("opacity-100");
    notif.classList.add("opacity-0");
    setTimeout(() => notif.remove(), 500); 
  }
}, 2500);
