<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false }" class="h-full">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>EQUIJOB - Job Applicant - Application Tracker</title>
    <link rel="icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" />
</head>

<body class="bg-[#FCFDFF] text-gray-800 font-sans antialiased h-full flex">

    <!-- Sidebar (desktop) -->
    <aside class="hidden lg:block w-[234px] bg-white h-screen fixed top-0 left-0 z-30">
        <x-applicant-sidebar />
    </aside>

    <!-- Sidebar overlay (mobile) -->
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition.opacity
        class="fixed inset-0 bg-black/50 z-20 lg:hidden">
    </div>

    <!-- Sidebar (mobile) -->
    <aside
        x-show="sidebarOpen"
        x-transition:enter="transition transform duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition transform duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 w-[234px] bg-white z-30 lg:hidden shadow-lg flex flex-col overflow-y-auto">
        <div class="flex flex-col h-full bg-[#c7d4f8]">
            <div class="flex justify-end p-4">
                <button @click="sidebarOpen = false" class="text-gray-800 hover:text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto">
                <x-applicant-sidebar />
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex flex-col flex-1 min-h-screen w-full lg:ml-[234px]">

        <!-- Header -->
        <header class="flex items-center justify-between w-full border-b border-gray-200 shadow-sm px-4 h-16 bg-white">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-800 lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <x-topbar :user="$user" :notifications="$user->notifications" :unreadNotifications="$user->unreadNotifications" />
        </header>

        <!-- Main Section -->
        <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6 bg-[#FCFDFF] flex justify-center items-center">
            @if ($errors->any() || session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <strong class="font-bold">Oops! Something went wrong.</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                    @if(session('error'))
                    <li>{{ session('error') }}</li>
                    @endif
                </ul>
            </div>
            @endif
            <div class="max-w-6xl mx-auto space-y-8">

                <!-- Title -->
                <h1 class="font-audiowide text-3xl md:text-4xl text-gray-800">
                    <span class="text-[#25324B]">Job Application </span>
                    <span class="text-[#26A4FF]">Tracker</span>
                </h1>



                <!-- Applicant ID Box -->
                <div class="w-full max-w-3xl bg-white shadow-md border border-gray-300 rounded-lg p-6">
                    <label for="applicantId" class="block text-lg font-medium text-gray-700 mb-2">
                        Job Application Number
                    </label>
                    <form action="{{route('applicant-application-tracker-show')}}" method="get">
                        @csrf
                        @method('GET')
                        <input
                            type="text"
                            id="jobApplicationNumber"
                            name="jobApplicationNumber"
                            class="w-full border border-gray-400 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Enter Job Application Number" />
                        <br>
                        <br>
                        <button class="bg-[#0073E6] text-white text-lg font-medium px-6 py-2 rounded-xl flex">
                            Search Application
                        </button>
                    </form>

                </div>

            </div>
        </main>

    </div>
</body>

</html>