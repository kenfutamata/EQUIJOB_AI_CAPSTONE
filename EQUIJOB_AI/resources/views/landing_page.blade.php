<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" cross origin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
    <script src="{{ asset('assets/landing_page/js/landing_page/landing_page.js') }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
</head>

<body class="bg-white text-gray-800">
    <x-landing-page-navbar />
    <div id="mobile-menu" class="hidden lg:hidden px-6 pb-4">
        <a href="#hero" class="block text-gray-600 hover:text-blue-600 py-2">Home</a>
        <a href="#why-us" class="block text-gray-600 hover:text-blue-600 py-2">Why Us</a>
        <a href="#partners" class="block text-gray-600 hover:text-blue-600 py-2">Partners</a>
        <a href="#resources" class="block text-gray-600 hover:text-blue-600 py-2">Resources</a>
        <a href="#reviews" class="block text-gray-600 hover:text-blue-600 py-2">Reviews</a>
        <a href="#faq" class="block text-gray-600 hover:text-blue-600 py-2">FAQ</a>
        <a href="#footer" class="block text-gray-600 hover:text-blue-600 py-2">Contact</a>
        <a href="#" class="block bg-blue-600 text-white text-center mt-2 px-4 py-2 rounded hover:bg-blue-700 transition duration-300">Register Now</a>
    </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="bg-blue-400 text-white py-20 px-6 lg:px-16">
        <div class="container mx-auto flex flex-col lg:flex-row items-center gap-12">
            <div class="lg:w-1/2 text-center lg:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-poppins leading-tight mb-4">Connecting Persons with Disabilities with Employers</h1>
                <p class="text-sm md:text-base leading-relaxed mb-8">Fostering disability-inclusive employment promotes equity, accessibility, and empowerment in the workforce, ensuring job seekers with disabilities have equal opportunities through inclusive hiring and strength-based pathways.</p>
                <a href="{{route('sign-in')}}" class="bg-green-600 hover:bg-green-700 text-white text-lg font-medium px-10 py-3 rounded-xl shadow-md transition duration-300 inline-block">Sign In Now</a>
            </div>
            <div class="lg:w-1/2">
                <img src="{{ asset('assets/photos/landing_page/landingpage_1.png') }}" alt="Inclusive workplace" class="w-full h-auto rounded-lg shadow-lg" />
            </div>
        </div>
    </section>

    <!-- Why Us Section -->
    <section id="why-us" class="py-16 lg:py-24 px-6 lg:px-16 bg-white">
        <div class="container mx-auto flex flex-col lg:flex-row-reverse items-center gap-12 lg:gap-20">
            <div class="lg:w-1/2">
                <h2 class="text-3xl md:text-4xl font-semibold font-poppins text-center lg:text-left mb-8 text-gray-800">Why Our Clients Trust Us</h2>
                <div class="space-y-5">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-teal-700 mt-1 flex-shrink-0" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.4276 25.5273C20.9065 25.5273 26.2074 20.4396 26.2074 14.2214C26.2074 8.00324 20.9065 2.91562 14.4276 2.91562C7.94873 2.91562 2.64783 8.00324 2.64783 14.2214C2.64783 20.4396 7.94873 25.5273 14.4276 25.5273Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9.42114 14.2214L12.7548 17.421L19.434 11.0219" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-lg text-gray-600 font-semibold leading-relaxed">Tailored for job seekers with disabilities, ensuring accessibility at every step.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-teal-700 mt-1 flex-shrink-0" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.4276 25.2243C20.9065 25.2243 26.2074 20.1367 26.2074 13.9185C26.2074 7.70031 20.9065 2.61269 14.4276 2.61269C7.94873 2.61269 2.64783 7.70031 2.64783 13.9185C2.64783 20.1367 7.94873 25.2243 14.4276 25.2243Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9.42114 13.9185L12.7548 17.1181L19.434 10.719" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-lg text-gray-600 font-semibold leading-relaxed">Offers resources and tools for equitable, inclusive hiring practices.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-teal-700 mt-1 flex-shrink-0" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.3666 24.8963C20.8175 24.8963 26.0956 19.8086 26.0956 13.5904C26.0956 7.37224 20.8175 2.28462 14.3666 2.28462C7.91572 2.28462 2.6377 7.37224 2.6377 13.5904C2.6377 19.8086 7.91572 24.8963 14.3666 24.8963Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9.38184 13.5904L12.7011 16.79L19.3514 10.3909" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-lg text-gray-600 font-semibold leading-relaxed">Committed to bringing about lasting and significant change in the job market.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-teal-700 mt-1 flex-shrink-0" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.4276 25.5463C20.9065 25.5463 26.2074 20.4587 26.2074 14.2405C26.2074 8.0223 20.9065 2.93468 14.4276 2.93468C7.94873 2.93468 2.64783 8.0223 2.64783 14.2405C2.64783 20.4587 7.94873 25.5463 14.4276 25.5463Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9.42114 14.2405L12.7548 17.4401L19.434 11.041" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-lg text-gray-600 font-semibold leading-relaxed">Meets more than one need using low-tech and high-tech methods.</p>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2">
                <img src="{{ asset('assets/photos/landing_page/landingpage_2.jpg') }}" alt="People collaborating" class="w-full h-auto rounded-lg shadow-lg" />
            </div>
        </div>
    </section>
    <!-- Resources Section -->
    <section id="resources" class="py-16 lg:py-24 px-6 lg:px-16 bg-white">
        <div class="container mx-auto">
            <h2 class="text-3xl md:text-4xl font-semibold font-poppins text-center mb-12 text-gray-800">Resources</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-gray-50 rounded-lg shadow-lg overflow-hidden">
                    <img src="{{ asset('assets/photos/landing_page/landing_page5.jpg') }}" alt="Inclusive Team Collaboration" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold font-poppins mb-2 text-gray-800">Inclusive Team Collaboration</h3>
                        <p class="text-gray-600 leading-relaxed">Illustrates a wheelchair user participating in a group forum, emphasizing cooperation, integration, and equal access to working environments.</p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="bg-gray-50 rounded-lg shadow-lg overflow-hidden">
                    <img src="{{ asset('assets/photos/landing_page/landing_page4.jpg') }}" alt="Equal Opportunity & Professionalism" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold font-poppins mb-2 text-gray-800">Equal Opportunity & Professionalism</h3>
                        <p class="text-gray-600 leading-relaxed">People with disabilities can occupy professional roles. A person in a wheelchair attends a business conference, proving capability and leadership.</p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="bg-gray-50 rounded-lg shadow-lg overflow-hidden">
                    <img src="{{ asset('assets/photos/landing_page/landing_page6.jpg') }}" alt="Accessibility in Tech & Learning" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold font-poppins mb-2 text-gray-800">Accessibility in Tech & Learning</h3>
                        <p class="text-gray-600 leading-relaxed">People with disabilities exhibit mastery of learning and technology, accessing online resources and showcasing diverse skills.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- FAQ Section -->
    <section id="faq" class="py-16 lg:py-24 px-6 lg:px-16 bg-white">
        <div class="container mx-auto flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
            <div class="lg:w-2/5">
                <img src="{{ asset('assets/photos/landing_page/landing_page7.jpg') }}" alt="Person asking a question" class="w-full h-auto rounded-lg shadow-lg" />
            </div>
            <div class="lg:w-3/5">
                <h2 class="text-3xl md:text-4xl font-semibold font-poppins mb-8 text-gray-800">Frequently Asked Questions</h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2 font-poppins">Can employers use EQUIJOB to improve their hiring practices?</h3>
                        <p class="text-gray-600 leading-relaxed">Yes, EQUIJOB provides employers with resources on disability inclusion and best practices for equitable hiring, helping to create a more inclusive workforce.</p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2 font-poppins">Is EQUIJOB accessible for users with various disabilities?</h3>
                        <p class="text-gray-600 leading-relaxed">Absolutely. Accessibility is a core principle. The platform is designed with features and considerations to support users with a wide range of disabilities.</p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2 font-poppins">How does EQUIJOB connect job seekers with employers?</h3>
                        <p class="text-gray-600 leading-relaxed">EQUIJOB uses a tailored matching system considering skills, experience, and accessibility needs, alongside facilitating inclusive interview processes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="footer" class="bg-gray-100 pt-16 pb-8 px-6 lg:px-16 text-gray-700 flex justify-center items-center">
        <div class="container mx-auto flex flex-col justify-center items-center">
            <img src="{{ asset('assets/photos/landing_page/equijob_logo.png') }}" alt="Equijob Logo" class="mx-auto w-32 h-auto mb-4">
            <p class="text-sm text-gray-600 leading-relaxed text-center max-w-xl">
                Fostering disability-inclusive employment for a more equitable and empowered workforce.
            </p>
            <p class="text-sm text-gray-600 leading-relaxed text-center max-w-xl">
                Copyright &copy; EQUIJOB. All rights reserved.
            </p>
        </div>
    </footer>

</body>

</html>