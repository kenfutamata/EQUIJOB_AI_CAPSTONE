<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQUIJOB - Job Rating Review</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}">
    <style>
        /* Ensure sidebar is always fixed */
        .sidebar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            width: 234px;
            height: 100vh;
            z-index: 40;
            background-color: #c3d2f7;
        }

        /* Ensure topbar is always fixed */
        .topbar-fixed {
            position: fixed;
            top: 0;
            left: 234px;
            right: 0;
            height: 64px;
            z-index: 30;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
        }

        /* Main content below topbar */
        .main-content-scroll {
            margin-left: 234px;
            margin-top: 64px;
            height: calc(100vh - 64px);
            overflow-y: auto;
            padding: 1rem;
        }

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

<body class="bg-white text-black">
    <div>
        <div class="sidebar-fixed">
            <x-admin-sidebar />
        </div>
        <div class="topbar-fixed">
            <x-topbar :user="$admin" :notifications="$notifications" :unreadNotifications="$unreadNotifications" />
        </div>
        <main class="main-content-scroll">
            @if(session('Success'))
            <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
                {{ session('Success') }}
            </div>
            @elseif(session('Delete_Success'))
            <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
                {{ session('Delete_Success') }}
            </div>
            @endif

            <div class="text-4xl font-audiowide mb-6 flex items-center justify-between">
                <div>
                    <span class="text-gray-800">Job Rating</span>
                    <span class="text-blue-500">Review</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{route('admin-feedback-job')}}" class="bg-blue-500 text-white px-2 py-1 rounded text-base">Job Rating</a>
                    <a href="{{route('admin-feedback-contact-us-system-review')}}" class="bg-blue-500 text-white px-2 py-1 rounded text-base">System Review</a>
                </div>
            </div>
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full text-sm text-center">
                    <thead class="bg-gray-100 font-semibold">
                        <tr>
                            <th class="px-2 py-2">First Name {!! sortArrow('firstName')!!}</th>
                            <th class="px-2 py-2">Last Name {!! sortArrow('lastName')!!}</th>
                            <th class="px-2 py-2">Position {!! sortArrow('position')!!}</th>
                            <th class="px-2 py-2">Company Name {!! sortArrow('companyName')!!}</th>
                            <th class="px-2 py-2">Feedback Type {!! sortArrow('companyName')!!}</th>
                            <th class="px-2 py-2">Rating {!! sortArrow('rating')!!}</th>
                            <th class="px-2 py-2">Status {!! sortArrow('status')!!}</th>
                            <th class="px-2 py-2">Actions {!! sortArrow('action')!!}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($feedbacks as $feedback)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-2">{{ $feedback->firstName }}</td>
                            <td class="px-2 py-2">{{ $feedback->lastName }}</td>
                            <td class="px-2 py-2">{{ $feedback->jobApplication->jobPosting->position ?? 'N/A' }}</td>
                            <td class="px-2 py-2">{{ $feedback->jobApplication->jobPosting->companyName}}</td>
                            <td class="px-2 py-2">{{ $feedback->feedbackType }}</td>
                            <td class="px-2 py-2">{{ $feedback->rating ?? 'Not Yet Rated'}}</td>
                            <td class="px-2 py-2">{{ $feedback->status}}</td>
                            <td class="px-2 py-2 space-y-1">
                                @if($feedback->status === 'Sent')
                                <button onclick="openviewDescriptionModal(this)" data-user='@json($feedback)' class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                                @elseif($feedback->status === 'Completed')
                                <button onclick="openviewDescriptionModal(this)" data-user='@json($feedback)' class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                                <button onclick="openDeleteModal({{ $feedback->id }})" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">No job ratings found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4 flex justify-center">
                    {!! $feedbacks->links('pagination::tailwind') !!}
                </div>
            </div>
        </main>
    </div>

    <div id="viewDescriptionModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto p-6 space-y-6 relative">
            <button onclick="closeviewDescriptionModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            <h2 class="text-xl font-bold mb-4">System Feedback</h2>
            <div class="space-y-2">
                <label class="block text-xs text-gray-500">First Name:</label>
                <input id="modal_firstName" class="w-full border rounded px-2 py-1" readonly placeholder="First Name">
                <label class="block text-xs text-gray-500">Last Name:</label>
                <input id="modal_lastName" class="w-full border rounded px-2 py-1" readonly placeholder="Last Name">
                <label class="block text-xs text-gray-500">Position:</label>
                <input id="modal_position" class="w-full border rounded px-2 py-1" readonly placeholder="Position">
                <label class="block text-xs text-gray-500">Company Name:</label>
                <input id="modal_companyName" class="w-full border rounded px-2 py-1" readonly placeholder="Company Name">
                <label class="block text-xs text-gray-500">Email Address:</label>
                <input id="modal_email" class="w-full border rounded px-2 py-1" readonly placeholder="Email">
                <label class="block text-xs text-gray-500">Phone Number:</label>
                <input id="modal_phoneNumber" class="w-full border rounded px-2 py-1" readonly placeholder="Phone Number">
                <label class="block text-xs text-gray-500">Feedback Type:</label>
                <input id="modal_feedbackType" class="w-full border rounded px-2 py-1" readonly placeholder="Feedback Type">
                <label class="block text-xs text-gray-500">Feedback Text:</label>
                <textarea id="modal_feedbackText" class="w-full border rounded px-2 py-1" readonly placeholder="Feedback Text"></textarea>
                <label class="block text-xs text-gray-500">Rating:</label>
                <input id="modal_rating" class="w-full border rounded px-2 py-1" readonly placeholder="Rating">
            </div>
        </div>
    </div>

    <div id="DeleteRoleModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Delete User?</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <form id="deleteuser" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-3 px-4 rounded-lg bg-gray-50">Yes</button>
            </form>
            <button onclick="closeDeleteModal()" class="w-full py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700">Cancel</button>
        </div>
    </div>
    <script>
        function openviewDescriptionModal(button) {
            const feedback = JSON.parse(button.getAttribute('data-user'));

            const position = feedback.job_application?.job_posting?.position;
            const companyName = feedback.job_application?.job_posting?.companyName;

            document.getElementById('modal_firstName').value = feedback.firstName;
            document.getElementById('modal_lastName').value = feedback.lastName;
            document.getElementById('modal_email').value = feedback.email;
            document.getElementById('modal_phoneNumber').value = feedback.phoneNumber;
            document.getElementById('modal_feedbackType').value = feedback.feedbackType;
            document.getElementById('modal_feedbackText').value = feedback.feedbackText;
            document.getElementById('modal_rating').value = feedback.rating ?? 'Not Yet Rated by Applicant';

            document.getElementById('modal_position').value = position ?? 'N/A';
            document.getElementById('modal_companyName').value = companyName ?? 'N/A';

            document.getElementById('viewDescriptionModal').classList.remove('hidden')
        }

        function closeviewDescriptionModal() {
            document.getElementById('viewDescriptionModal').classList.add('hidden');
        }

        function openDeleteModal(userId) {
            const form = document.getElementById('deleteuser');
            form.action = `/EQUIJOB/Admin/Manage-User-Applicants/Delete/${userId}`;
            document.getElementById('DeleteRoleModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('DeleteRoleModal').classList.add('hidden');
        }

        window.addEventListener('click', function(e) {
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
    </script>
</body>

</html>