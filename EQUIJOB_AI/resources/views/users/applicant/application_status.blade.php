<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false }" class="h-full">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>EQUIJOB - Job Applicant - Application Status</title>
    <link rel="icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" />
    <style>
        .progress-bar {
            transition: width 0.5s ease-in-out;
        }
    </style>
</head>

<body class="bg-[#FCFDFF] text-gray-800 font-sans antialiased h-full flex">

    <!-- Sidebar (desktop) -->
    <aside class="hidden lg:block w-[234px] bg-white h-screen fixed top-0 left-0 z-30">
        <x-applicant-sidebar />
    </aside>

    <!-- Sidebar overlay (mobile) -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
        x-transition.opacity class="fixed inset-0 bg-black/50 z-20 lg:hidden">
    </div>

    <!-- Sidebar (mobile) -->
    <aside x-show="sidebarOpen"
        x-transition:enter="transition transform duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition transform duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 w-[234px] bg-white z-30 lg:hidden shadow-lg">
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
    <!-- Main Content -->
    <div class="flex flex-col flex-1 min-h-screen w-full lg:ml-[234px]">

        <!-- Header -->
        <header class="flex items-center justify-between border-b border-gray-200 shadow-sm px-4 h-16 bg-white">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-800 lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <x-topbar :user="$user" :notifications="$user->notifications" :unreadNotifications="$user->unreadNotifications" />
        </header>

        <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6">
            @if($application)
            @php
            $stages = [
            'Pending' => 'Applied',
            'For Interview' => 'Interview',
            'On-Offer' => 'Offer',
            'Hired' => 'Hired',
            'Withdraw' => 'Withdraw',
            'Rejected' => 'Rejected',
            ];
            $currentStatus = $application->status;
            $isRejected = $currentStatus === 'Rejected';
            $progressBarStages = ['Pending', 'For Interview', 'On-Offer', 'Hired'];
            $currentStageIndex = array_search($currentStatus, $progressBarStages);
            $totalProgressStages = count($progressBarStages); 

            if($currentStageIndex !== false && $totalProgressStages > 1){
                $progressWidth = ($currentStageIndex / ($totalProgressStages - 1)) * 100 . '%';
            }
            else{
                $progressWidth = '0%'; 
            }
            @endphp


            <div class="bg-white shadow-lg border border-gray-200 rounded-lg p-6 sm:p-8">
                <div class="relative">
                    <div class="absolute top-2.5 left-0 w-full h-1.5 bg-gray-200 rounded-full"></div>

                    <div class="progress-bar absolute top-2.5 left-0 h-1.5 bg-blue-500 rounded-full" style="width: {{ $progressWidth }};"></div>

                    <div class="flex justify-between items-start">
                        @foreach(['Pending' => 'Applied', 'For Interview' => 'Interview', 'On-Offer' => 'Offer', 'Hired' => 'Hired'] as $status => $label)
                        @php
                        $isCompleted = (array_search($currentStatus, array_keys($stages)) >= array_search($status, array_keys($stages))) && !$isRejected;
                        @endphp
                        <div class="flex flex-col items-center z-10">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $isCompleted ? 'bg-blue-500' : 'bg-gray-300' }}">
                                @if($isCompleted)
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                @endif
                            </div>
                            <span class="mt-2 text-sm font-semibold {{ $isCompleted ? 'text-blue-600' : 'text-gray-500' }}">{{ $label }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mt-8 rounded-lg p-6 w-full max-w-4xl mx-auto
                        {{ $isRejected ? 'bg-red-100 border-l-8 border-red-500' : 'bg-blue-100 border-l-8 border-blue-500' }}">

                <h2 class="text-xl font-bold {{ $isRejected ? 'text-red-800' : 'text-blue-800' }}">
                    Current Status: {{ $currentStatus }}
                </h2>

                <div class="mt-4 text-gray-700 space-y-2">
                    @if($currentStatus === 'Pending')
                    <p>Thank you for your application! The Job Provider is currently reviewing it. You will be notified if you are selected to move to the next stage.</p>
                    @elseif($currentStatus === 'For Interview')
                    <p>Congratulations! You have been selected for an interview. Please check your email for the details.</p>
                    <div class="bg-white p-4 rounded-md border">
                        <p><strong>Interview Date:</strong> {{ $application->interviewDate ? $application->interviewDate->format('F j, Y'): TBD}}</p>
                        <p><strong>Interview Time:</strong> {{ $application->interviewTime ? $application->interviewTime->format('g:i A') : TBD }}</p>
                        <p><strong>Meeting Link:</strong> <a href="{{ $application->interviewLink }}" target="_blank" class="text-blue-600 underline">Click to Join</a></p>
                    </div>
                    @elseif($currentStatus === 'On-Offer' || $currentStatus === 'Hired')
                    <p>Good News! The Job Provider has offer the position of {{$application->JobPosting->position}} on offer. If you wish to accept. please head to your Manage Job Applications to accept our offer .</p>
                    @elseif($currentStatus === 'Rejected')
                    <p>Thank you for your interest. After careful consideration, the hiring team has decided to move forward with other candidates at this time. Kindly Check your email for further details.</p>
                    @elseif($currentStatus === 'Withdrawn')
                    <p>Your Application has been Withdrawn.</p>
                    @if($application->remarks)
                    <p class="mt-2 text-sm text-gray-600"><strong>Reason:</strong> {{ $application->remarks }}</p>
                    @endif
                    @endif
                </div>
            </div>
            @else
            <div class="mt-8 bg-yellow-100 border-l-8 border-yellow-500 text-yellow-800 rounded-lg p-6 w-full max-w-4xl mx-auto text-center">
                <h2 class="text-2xl font-bold">Application Not Found</h2>
                <p class="mt-2">We couldn't find an application with that ID associated with your account. Please check your email for your Job Application Number.</p>
            </div>
            @endif
    </div>
    </main>
    </div>
</body>

</html>