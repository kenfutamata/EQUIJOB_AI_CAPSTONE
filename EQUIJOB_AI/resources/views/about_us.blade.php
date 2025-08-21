<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EQUIJOB - About Us</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo (2).png')}}">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>

<body class="bg-white text-gray-800">
  <x-landing-page-navbar />

  <!-- Hero Section -->
  <section class="relative bg-blue-800 text-white">
    <img src="{{ asset('assets/photos/landing_page/about_us_title.jpg') }}" alt="Main background"
      class="absolute inset-0 w-full h-full object-cover opacity-40">
    <div class="relative max-w-4xl mx-auto px-6 py-32 text-center space-y-8">
      <h1 class="text-5xl md:text-6xl font-bold">About Us</h1>
      <p class="text-lg md:text-xl leading-relaxed">
        “Our capstone project, EQUIJOB, is an AI-powered web-based platform that we developed to help persons with
        disabilities find inclusive job opportunities. With EQUIJOB, we aim to make job searching easier by matching PWD
        job seekers to employers who practice inclusive hiring.”
      </p>
      <a href="#about"
        class="inline-block bg-yellow-300 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-400 transition">
        See More
      </a>
    </div>
  </section>

  <section id="about" class="py-20 px-6 md:px-16 text-center">
    <h2 class="text-4xl font-semibold mb-6">What is EQUIJOB?</h2>
    <p class="max-w-3xl mx-auto text-lg leading-relaxed">
      EQUIJOB is an innovative, AI-powered web-based platform designed to bridge the gap between job seekers with
      disabilities and inclusive employers. Our platform streamlines the job matching process using intelligent
      algorithms to connect individuals with the right opportunities based on their skills, preferences, and
      accessibility needs.
    </p>
    <div class="mt-12 flex justify-center">
      <img src="{{ asset('assets/photos/landing_page/mcctest.png') }}" alt="Illustration" class="rounded-2xl shadow-lg">
    </div>
  </section>

  <!-- Mission & Vision -->
  <section class="py-20 bg-gray-50 px-6 md:px-16">
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10">
      <div class="bg-blue-600 text-white p-10 rounded-2xl shadow-lg">
        <h3 class="text-3xl font-semibold mb-4">Our Mission</h3>
        <p class="text-lg leading-relaxed">
          To empower persons with disabilities by providing equal access to employment opportunities through smart,
          inclusive, and accessible job-matching technology.
        </p>
      </div>
      <div class="bg-blue-600 text-white p-10 rounded-2xl shadow-lg">
        <h3 class="text-3xl font-semibold mb-4">Our Vision</h3>
        <p class="text-lg leading-relaxed">
          A future where inclusive employment is the standard where everyone, regardless of ability, has access to
          meaningful work and a supportive workplace environment.
        </p>
      </div>
    </div>
  </section>

<section class="py-20 px-6 md:px-16 bg-white">
  <h2 class="text-4xl font-semibold text-center mb-12">Meet Our Team</h2>

  <!-- Main Grid (Top 3 Members) -->
  <div class="max-w-6xl mx-auto grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
    <!-- Member -->
    <div class="bg-gray-50 p-6 rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2">
      <img src="{{ asset('assets/photos/landing_page/kezekiah.jpg') }}" alt="Team Member" class="w-24 h-24 mx-auto rounded-full mb-4">
      <h3 class="text-xl font-medium text-center">Kezekiah Yatong</h3>
      <p class="text-blue-600 text-center font-medium">Project Manager</p>
      <p class="text-gray-600 text-sm mt-2 text-center">Leads the team and ensures timely completion of the capstone.</p>
    </div>

    <div class="bg-gray-50 p-6 rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2">
      <img src="{{ asset('assets/photos/landing_page/kento.jpg') }}" alt="Team Member" class="w-24 h-24 mx-auto rounded-full mb-4">
      <h3 class="text-xl font-medium text-center">Kento Futamata</h3>
      <p class="text-blue-600 text-center font-medium">Hacker</p>
      <p class="text-gray-600 text-sm mt-2 text-center">Develops the system and core functionality for the capstone.</p>
    </div>

    <div class="bg-gray-50 p-6 rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2">
      <img src="{{ asset('assets/photos/landing_page/janine.jpg') }}" alt="Team Member" class="w-24 h-24 mx-auto rounded-full mb-4">
      <h3 class="text-xl font-medium text-center">Janine Alolod</h3>
      <p class="text-blue-600 text-center font-medium">Hipster</p>
      <p class="text-gray-600 text-sm mt-2 text-center">Focuses on design and user experience for engaging interfaces.</p>
    </div>
  </div>

  <div class="max-w-4xl mx-auto mt-16 grid grid-cols-1 md:grid-cols-2 gap-10">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-xl hover:shadow-2xl transition transform hover:-translate-y-2">
      <div class="p-6 text-center">
        <img src="{{ asset('assets/photos/landing_page/sara.jpg') }}" alt="Team Member" class="w-20 h-20 mx-auto rounded-full mb-4">
        <h3 class="text-lg font-semibold">Sara Pahara</h3>
        <p class="text-blue-600 font-medium">Hustler</p>
        <p class="text-gray-600 text-sm mt-2">Promotes the project, documents progress, and ensures clarity.</p>
      </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-xl hover:shadow-2xl transition transform hover:-translate-y-2">

      <div class="p-6 text-center">
        <img src="{{ asset('assets/photos/landing_page/lance.jpg') }}" alt="Team Member" class="w-20 h-20 mx-auto rounded-full mb-4">
        <h3 class="text-lg font-semibold">Lance Paul Montenmar</h3>
        <p class="text-blue-600 font-medium">Tester</p>
        <p class="text-gray-600 text-sm mt-2">Tests the system for errors and ensures functionality.</p>
      </div>
    </div>
  </div>
</section>

  <!-- Footer -->
  <footer class="bg-gray-100 pt-16 pb-8 px-6 lg:px-16 text-gray-700">
    <div class="max-w-6xl mx-auto">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
        <div class="lg:col-span-2">
          <a href="#" class="text-2xl font-bold text-blue-600 font-poppins mb-4 inline-block">EQUIJOB</a>
          <img src="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" alt="Equijob Logo" class="w-32 h-auto mb-4">
          <p class="text-sm text-gray-600 leading-relaxed">
            Fostering disability-inclusive employment for a more equitable and empowered workforce.
          </p>
        </div>
      </div>
    </div>
  </footer>

</body>

</html>
