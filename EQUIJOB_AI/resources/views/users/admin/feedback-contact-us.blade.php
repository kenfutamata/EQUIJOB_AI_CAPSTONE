<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQUIJOB - Admin System Review</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
    <link href="{{ asset('assets/admin/feedbacks/css/feedback-contact-us.css') }}" rel="stylesheet">
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
                    <span class="text-gray-800">System</span>
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
                            <th class="px-2 py-2 max-w-[150px] break-words">Email {!! sortArrow('email')!!}</th>
                            <th class="px-2 py-2">Phone Number {!! sortArrow('phoneNumber')!!}</th>
                            <th class="px-2 py-2">Feedback Type {!! sortArrow('feedbackType')!!}</th>
                            <th class="px-2 py-2">Action {!! sortArrow('action')!!}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($feedbacks as $feedback)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-2">{{ $feedback->firstName }}</td>
                            <td class="px-2 py-2">{{ $feedback->lastName }}</td>
                            <td class="px-2 py-2 break-all max-w-[150px]">{{ $feedback->email }}</td>
                            <td class="px-2 py-2">{{ $feedback->phoneNumber }}</td>
                            <td class="px-2 py-2">{{ $feedback->feedbackType }}</td>
                            <td class="px-2 py-2 space-y-1">
                                <button onclick="openviewDescriptionModal(this)" data-user='@json($feedback)' class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                                <button onclick="openDeleteFeedbackForm('{{ route('admin-feedback-system-review-delete', $feedback->id) }}')" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                            </td>
                        </tr>
                        @endforeach
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
                <label class="block text-xs text-gray-500">Email Address:</label>
                <input id="modal_email" class="w-full border rounded px-2 py-1" readonly placeholder="Email">
                <label class="block text-xs text-gray-500">Phone Number:</label>
                <input id="modal_phoneNumber" class="w-full border rounded px-2 py-1" readonly placeholder="Phone Number">
                <label class="block text-xs text-gray-500">Feedback Type:</label>
                <input id="modal_feedbackType" class="w-full border rounded px-2 py-1" readonly placeholder="Feedback Type">
                <label class="block text-xs text-gray-500">Feedback Text:</label>
                <textarea id="modal_feedbackText" class="w-full border rounded px-2 py-1" readonly placeholder="Feedback Text"></textarea>
            </div>
        </div>
    </div>

    <div id="deleteFeedbackForm" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Delete Feedback?</h3>
                <button onclick="closeDeleteFeedbackForm()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <form id="deleteFeedback" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-3 px-4 rounded-lg bg-green-200">Yes</button>
            </form>
            <button onclick="closeDeleteFeedbackForm()" class="w-full py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700">Cancel</button>
        </div>
    </div>
    <script src="{{ asset('assets/admin/feedbacks/js/feedback-contact-us.js') }}"></script>
</body>

</html>