<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Contact Us</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/landing_page/css/contact_us.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/landing_page/js/landing_page/contact_us.js') }}"></script>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo (2).png')}}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-white text-gray-800">
    <x-landing-page-navbar />

    <main class="max-w-6xl mx-auto px-6 py-16">
        @if(session('success'))
        <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
            {{ session('success') }}
        </div>
        @elseif(session('error'))
        <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50">
            {{ session('error') }}
        </div>
        @endif
        <h1 class="text-5xl font-semibold text-center mb-8">CONTACT US</h1>
        <h2 class="text-3xl font-bold mb-4">How may we help you?</h2>
        <p class="text-xl mb-8 max-w-3xl">For any queries or concerns, send us a message using the form below. We'll get back to you as soon as possible.</p>


        <form class="space-y-8" action="{{route('contact-us-submit')}}" method="POST">
            @csrf
            <div>
                <label class="block text-2xl font-semibold text-gray-700 mb-2">First Name</label>
                <input type="text" name="firstName" id="firstName" placeholder="Enter your First Name" class="w-full p-4 border-2 border-gray-300 rounded-lg text-lg" pattern="[A-Za-z\s]+" required>
            </div>
            <div>
                <label class="block text-2xl font-semibold text-gray-700 mb-2">Last Name</label>
                <input type="text" name="lastName" id="lastName" placeholder="Enter your Last Name" class="w-full p-4 border-2 border-gray-300 rounded-lg text-lg" pattern="[A-Za-z\s]+" required>
            </div>
            <div>
                <label class="block text-2xl font-semibold text-gray-700 mb-2">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your Email Address" class="w-full p-4 border-2 border-gray-300 rounded-lg text-lg" required>
            </div>

            <div>
                <label class="block text-2xl font-semibold text-gray-700 mb-2">Contact Number</label>
                <input type="tel" name="phoneNumber" id="phoneNumber" placeholder="Enter your Contact Number" class="w-full p-4 border-2 border-gray-300 rounded-lg text-lg" pattern="^(\+?\d{1,3})?[-.\s()]?\d{3,4}[-.\s()]?\d{3,4}[-.\s()]?\d{3,4}$" required>
            </div>
            <div>
                <label class="block text-2xl font-semibold text-gray-700 mb-2">Message</label>
                <textarea rows="5" class="w-full p-4 border-2 border-gray-300 rounded-lg text-lg" name="feedbackText" id="feedbackText" placeholder="Concerns..."></textarea>
            </div>
            <div>
                <label class="block text-2xl font-semibold text-gray-700 mb-2">Which EQUIJOB Topic Fits your needs?</label>
                <select class="w-full p-4 border-2 border-gray-300 rounded-lg text-lg" id="feedbackType" name="feedbackType" required>
                    <option selected disabled>Select EQUIJOB Topic</option>
                    <option>Job Application Issues</option>
                    <option>AI-Job Matching Issues</option>
                    <option>Resume Builder Problems</option>
                    <option>Other</option>
                </select>
            </div>
            <p class="text-lg text-gray-600 max-w-3xl">
                By contacting us and submitting your personal information, you represent that you have read and agreed to our Collection Notice and Privacy Policy.
            </p>

            <div class="text-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 text-xl font-bold rounded-2xl shadow-md border-2 border-black">
                    Submit
                </button>
            </div>
        </form>
    </main>

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