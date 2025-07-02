@props(['user', 'notifications' => collect(), 'unreadNotifications' => collect()])
@extends('layouts.app')

<div class="flex flex-col flex-1">
  <div class="flex justify-end items-center h-16 px-4 border-b border-gray-300 bg-white">

    <div x-data="{ open: false }" class="relative mr-6">
      <button @click="open = !open" class="relative focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($unreadNotifications->isNotEmpty())
        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-600 rounded-full"></span>
        @endif
      </button>

      <div x-show="open"
        @click.away="open = false"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 bg-white border border-gray-300 rounded-lg shadow-lg z-50">

        <div class="px-4 py-2 font-semibold text-sm text-gray-800 border-b">Notifications</div>

        @if($unreadNotifications->isNotEmpty())
        <div class="border-b">
          <div class="px-4 py-2 text-xs font-bold text-gray-500 uppercase">Unread</div>
          <div class="max-h-48 overflow-y-auto">
            @foreach($unreadNotifications as $notification)
            <div class="relative group border-t hover:bg-gray-100">
              <div 
                onclick="openNotificationModal(event)" 
                class="block px-4 py-2 pr-8 cursor-pointer" 
                data-position="{{ $notification->data['position'] ?? 'N/A' }}"
                data-message="{{ $notification->data['message'] ?? 'New notification' }}"
              >
                <p class="text-sm text-gray-800">{{ $notification->data['message'] ?? 'New notification' }}</p>
                <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
              </div>

              <form method="POST" action="{{ route('notification-delete', $notification->id) }}"
                class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">&times;</button>
              </form>
            </div>
            @endforeach
          </div>
        </div>
        @endif

        <div>
          <div class="px-4 py-2 text-xs font-bold text-gray-500 uppercase">Recent</div>
          <div class="max-h-48 overflow-y-auto">
            @php
              $readNotifications = $notifications->whereNotIn('id', $unreadNotifications->pluck('id'))->take(5);
            @endphp
            @forelse($readNotifications as $notification)
            <div class="relative group border-t hover:bg-gray-100">
              <div 
                onclick="openNotificationModal(event)" 
                class="block px-4 py-2 pr-8 cursor-pointer text-gray-600"
                data-position="{{ $notification->data['position'] ?? 'N/A' }}"
                data-message="{{ $notification->data['message'] ?? 'Notification' }}"
              >
                <p class="text-sm">{{ $notification->data['message'] ?? 'Notification' }}</p>
                <p class="text-xs">{{ $notification->created_at->diffForHumans() }}</p>
              </div>
              <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}"
                class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-400 hover:text-red-600 text-sm">&times;</button>
              </form>
            </div>
            @empty
            <div class="px-4 py-2 text-gray-500 text-sm">No recent notifications</div>
            @endforelse
          </div>
        </div>

      </div>
    </div>

    @if($user->role == 'Applicant')
    <a href="{{ route('applicant-profile') }}" class="flex items-center border border-black px-2 py-1 bg-white hover:bg-gray-100 transition rounded w-[170px] h-[50px]">
      <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('assets/applicant/applicant-dashboard/profile_pic.png') }}" alt="User avatar" class="rounded-full w-10 h-11 mr-2" />
      <div class="text-xs font-medium">
        <div class="text-[11px]">{{ $user->first_name }} {{ $user->last_name }}</div>
        <div class="text-[12px] text-gray-600">{{ $user->role }}</div>
      </div>
    </a>
    @elseif($user->role == 'Job Provider')
    <a href="{{ route('job-provider-profile') }}" class="flex items-center border border-black px-2 py-1 bg-white hover:bg-gray-100 transition rounded w-[170px] h-[50px]">
      <img src="{{ asset('assets/job-provider/job-provider-dashboard/profile_pic.png') }}" alt="User avatar" class="rounded-full w-10 h-11 mr-2" />
      <div class="text-xs font-medium">
        <div class="text-[11px]">{{ $user->first_name }} {{ $user->last_name }}</div>
        <div class="text-[12px] text-gray-600">{{ $user->role }}</div>
      </div>
    </a>
    @elseif($user->role == 'Admin')
    <div class="flex items-center border border-black px-2 py-1 bg-white rounded w-[170px] h-[50px]">
      <img src="{{ asset('assets/job-provider/job-provider-dashboard/profile_pic.png') }}" alt="User avatar" class="rounded-full w-10 h-11 mr-2" />
      <div class="text-xs font-medium">
        <div class="text-[11px]">{{ $user->first_name }}</div>
        <div class="text-[12px] text-gray-600">{{ $user->role }}</div>
      </div>
    </div>
    @endif
  </div>
</div>

<!-- Modal -->
<div id="viewNotification" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 space-y-6 relative">
    <button onclick="closeNotificationModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
    <h2 class="text-xl font-bold mb-4">Notifications</h2>
    <div>
      <label class="block text-xs text-gray-500">Notification Display</label>
      <textarea  id="modal.message" class="w-full border rounded px-2 py-1 w-100" readonly></textarea>
    </div>
  </div>
</div>

<script>
  function openNotificationModal(event) {
    const element = event.target.closest('[data-position]');
    const modal = document.getElementById('viewNotification');
    const positionField = document.getElementById('modal.position');
    const messageField = document.getElementById('modal.message');
    const message = element.getAttribute('data-message') || 'No message available';
    messageField.value = message;


    modal.classList.remove('hidden');
  }

  function closeNotificationModal() {
    document.getElementById('viewNotification').classList.add('hidden');
  }
</script>
