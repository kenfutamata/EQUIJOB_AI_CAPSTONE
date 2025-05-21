<script src="https://cdn.tailwindcss.com"></script>

<header class="bg-blue-300 w-full shadow-sm">
  <div class="max-w-screen-xl mx-auto flex items-center justify-between px-4 sm:px-8 py-3 relative">
    <!-- Logo and Brand Name Link -->
    <a href="{{ route('landing-page') }}" class="flex items-center gap-2 no-underline">
      <img src="{{ asset('assets/photos/landing_page/equijob_logo.png') }}" alt="EquiJob Logo" class="w-12 h-12 object-contain" />
      <span class="text-sm font-bold text-[#0b3c5d]">EquiJob</span>
    </a>

    <!-- Desktop Nav Links -->
    <nav class="hidden md:flex gap-8 text-sm font-semibold text-white">
      <a href="#" class="text-gray-100 hover:text-green-300 transition">About Us</a>
      <a href="#" class="text-gray-100 hover:text-green-300 transition">Contact Us</a>
    </nav>

    <!-- Sign In Button (Desktop) -->
    <div class="hidden md:block">
      <a href="{{ route('sign-in') }}" class="bg-blue-400 text-white text-sm font-semibold px-6 py-2 rounded-full shadow-md border border-gray-700">
        SIGN IN
      </a>
    </div>

    <!-- Mobile Menu Button -->
    <div class="md:hidden">
      <button id="mobile-menu-button" class="text-gray-700 focus:outline-none">
        <!-- Hamburger Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round"
                stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div id="mobile-menu" class="absolute top-full right-4 mt-2 w-48 bg-white rounded-lg shadow-lg border hidden md:hidden z-50">
      <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">About Us</a>
      <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">Contact Us</a>
      <a href="{{ route('sign-in') }}" class="block px-4 py-2 text-sm text-blue-600 font-semibold hover:bg-blue-100">Sign In</a>
    </div>
  </div>
</header>

<!-- Toggle Script -->
<script>
  const menuBtn = document.getElementById('mobile-menu-button');
  const mobileMenu = document.getElementById('mobile-menu');

  menuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
  });
</script>