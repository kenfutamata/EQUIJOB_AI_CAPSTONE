<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQUIJOB - Applicant Feedback</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
    <style>
        #notification-bar {
            transition: opacity 0.5s;
        }
    </style>
</head>
@php
function sortArrow($column) {
    $currentSort = request('sort');
    $direction = request('direction') === 'asc' ? 'desc' : 'asc';
    $arrow = request('sort') === $column
        ? (request('direction') === 'asc' ? '↑' : '↓')
        : '↕';
    $params = array_merge(request()->all(), ['sort' => $column, 'direction' => $direction]);
    $url = request()->url() . '?' . http_build_query($params);
    return "<a href=\"$url\" class=\"text-xs\">$arrow</a>";
}
@endphp

<body x-data="{ sidebarOpen: false }" class="bg-gray-50 text-black flex min-h-screen">
    
    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    <!-- Mobile Sidebar -->
    <aside x-show="sidebarOpen" x-transition:enter="transition transform duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition transform duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 w-[234px] z-50 lg:hidden flex flex-col" style="background-color: #c3d2f7;">
        <div class="flex justify-end p-4">
            <button @click="sidebarOpen = false" class="text-gray-800 hover:text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <x-applicant-sidebar />
    </aside>

    <!-- Static Desktop Sidebar -->
    <aside class="w-[234px] hidden lg:block h-screen fixed top-0 left-0 z-20" style="background-color: #c3d2f7;">
        <x-applicant-sidebar />
    </aside>

    <div class="flex flex-col flex-1 lg:ml-[234px]">
        <header class="fixed top-0 left-0 lg:left-[234px] right-0 h-16 z-10 bg-white border-b border-gray-200 flex items-center">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-4 text-gray-600 hover:text-gray-900 focus:outline-none">
                <span class="sr-only">Open sidebar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div class="flex-1">
                <x-topbar :user="$user" :notifications="$notifications" :unreadNotifications="$unreadNotifications" />
            </div>
        </header>

        <main class="mt-16 p-4 sm:p-6 flex-1">
            @if(session('Success'))
            <div id="notification-bar" class="fixed top-20 sm:top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50 w-11/12 max-w-lg text-center">
                {{ session('Success') }}
            </div>
            @elseif(session('Delete_Success'))
            <div id="notification-bar" class="fixed top-20 sm:top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50 w-11/12 max-w-lg text-center">
                {{ session('Delete_Success') }}
            </div>
            @endif

            <div class="text-3xl md:text-4xl font-semibold mb-6">
                <span class="text-gray-800">Applicant Feedback</span>
                <span class="text-blue-500">Review</span>
            </div>

            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full text-sm text-left sm:text-center">
                    <thead class="bg-gray-100 font-semibold">
                        <tr>
                            <th class="px-3 py-2">Name {!! sortArrow('firstName')!!}</th>
                            <th class="px-3 py-2 hidden md:table-cell">Position {!! sortArrow('position')!!}</th>
                            <th class="px-3 py-2 hidden lg:table-cell">Company Name {!! sortArrow('companyName')!!}</th>
                            <th class="px-3 py-2">Feedback Type {!! sortArrow('feedbackType')!!}</th>
                            <th class="px-3 py-2">Status {!! sortArrow('status')!!}</th>
                            <th class="px-3 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($feedbacks as $feedback)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2">{{ $feedback->firstName }} {{ $feedback->lastName }}</td>
                            <td class="px-3 py-2 hidden md:table-cell">{{ $feedback->jobPosting->position }}</td>
                            <td class="px-3 py-2 hidden lg:table-cell">{{ $feedback->jobPosting->companyName }}</td>
                            <td class="px-3 py-2">{{ $feedback->feedbackType }}</td>
                            <td class="px-3 py-2">{{ $feedback->status }}</td>
                            <td class="px-3 py-2 space-y-1 sm:space-y-0 sm:space-x-1">
                                <button onclick="openviewDescriptionModal(this)" data-user='@json($feedback)' class="bg-blue-500 text-white px-2 py-1 rounded w-full sm:w-auto">View</button>
                                @if($feedback->status === 'Sent')
                                <button onclick="openFeedbackSubmitModal({{$feedback}})" data-user='@json($feedback)' class="bg-green-500 text-white px-2 py-1 rounded w-full sm:w-auto">Submit</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                           <td colspan="6" class="text-center py-4 text-gray-500">No feedback entries found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($feedbacks->hasPages())
                <div class="p-4">
                    {!! $feedbacks->links('pagination::tailwind') !!}
                </div>
                @endif
            </div>
        </main>
    </div>

    <!-- MODALS (Structure is already responsive) -->
    <div id="viewDescriptionModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto p-6 space-y-6 relative">
            <button onclick="closeviewDescriptionModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            <h2 class="text-xl font-bold mb-4">Job Feedback</h2>
            <div class="space-y-2">
                <label class="block text-xs text-gray-500">First Name:</label>
                <input id="modal_firstName" class="w-full border rounded px-2 py-1 bg-gray-100" readonly>
                <label class="block text-xs text-gray-500">Last Name:</label>
                <input id="modal_lastName" class="w-full border rounded px-2 py-1 bg-gray-100" readonly>
                <label class="block text-xs text-gray-500">Email Address:</label>
                <input id="modal_email" class="w-full border rounded px-2 py-1 bg-gray-100" readonly>
                <label class="block text-xs text-gray-500">Position:</label>
                <input id="modal_position" class="w-full border rounded px-2 py-1 bg-gray-100" readonly>
                <label class="block text-xs text-gray-500">Company Name:</label>
                <input id="modal_companyName" class="w-full border rounded px-2 py-1 bg-gray-100" readonly>
                <label class="block text-xs text-gray-500">Phone Number:</label>
                <input id="modal_phoneNumber" class="w-full border rounded px-2 py-1 bg-gray-100" readonly>
                <label class="block text-xs text-gray-500">Feedback Type:</label>
                <input id="modal_feedbackType" class="w-full border rounded px-2 py-1 bg-gray-100" readonly>
                <label class="block text-xs text-gray-500">Feedback Text:</label>
                <textarea id="modal_feedbackText" class="w-full border rounded px-2 py-1 bg-gray-100" readonly></textarea>
                <label class="block text-xs text-gray-500">Rating:</label>
                <input id="modal_rating" class="w-full border rounded px-2 py-1 bg-gray-100" readonly>
            </div>
        </div>
    </div>

    <div id="submitFeedbackDetails" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Your Job Feedback</h3>
                <button onclick="closeFeedbackSubmitModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <form id="submitFeedback" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="feedbackText" class="block text-sm font-medium text-gray-700">Your Feedback:</label>
                        <textarea id="feedbackText" name="feedbackText" rows="4" class="mt-1 w-full border rounded px-2 py-1 shadow-sm" required></textarea>
                    </div>
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700">Your Rating (1-5):</label>
                        <select name="rating" id="rating" class="mt-1 w-full border rounded px-2 py-1 shadow-sm" required>
                            <option value="" disabled selected>Select a rating</option>
                            <option value="1">1 - Poor</option>
                            <option value="2">2 - Fair</option>
                            <option value="3">3 - Good</option>
                            <option value="4">4 - Very Good</option>
                            <option value="5">5 - Excellent</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-2 px-4 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700">Submit Feedback</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openviewDescriptionModal(button) {
            const feedback = JSON.parse(button.getAttribute('data-user'));
            const position = feedback.job_posting?.position;
            const companyName = feedback.job_posting?.companyName;
            document.getElementById('modal_firstName').value = feedback.firstName || 'N/A';
            document.getElementById('modal_lastName').value = feedback.lastName || 'N/A';
            document.getElementById('modal_email').value = feedback.email || 'N/A';
            document.getElementById('modal_phoneNumber').value = feedback.phoneNumber || 'N/A';
            document.getElementById('modal_feedbackType').value = feedback.feedbackType || 'N/A';
            document.getElementById('modal_position').value = position || 'N/A';
            document.getElementById('modal_companyName').value = companyName || 'N/A';
            document.getElementById('modal_feedbackText').value = feedback.feedbackText || 'No feedback provided yet.';
            document.getElementById('modal_rating').value = feedback.rating || 'Not rated yet.';
            document.getElementById('viewDescriptionModal').classList.remove('hidden');
        }

        function closeviewDescriptionModal() {
            document.getElementById('viewDescriptionModal').classList.add('hidden');
        }

        function openFeedbackSubmitModal(feedback) {
            const modal = document.getElementById('submitFeedbackDetails');
            const form = document.getElementById('submitFeedback');
            const url = `{{ url('/Applicant/Applicant-Feedback') }}/${feedback.id}`;
            form.action = url;
            modal.classList.remove('hidden');
        }

        function closeFeedbackSubmitModal() {
            document.getElementById('submitFeedbackDetails').classList.add('hidden');
        }

        // Auto-hide notification bar
        setTimeout(() => {
            const notif = document.getElementById('notification-bar');
            if (notif) {
                notif.style.opacity = '0';
                setTimeout(() => notif.style.display = 'none', 500);
            }
        }, 3000);
    </script>
</body>

</html>