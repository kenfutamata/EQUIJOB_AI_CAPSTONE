  @props(['user'])
  <!-- Main content -->
  <div class="flex flex-col flex-1">
    <!-- Top bar -->
    <div class="flex justify-end items-center h-16 px-4 border-b border-gray-300 bg-white">
      <!-- Notification Icon -->
      <div class="mr-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
      </div>

      <!-- Profile box -->
    @if($user->role == 'Applicant')
      <a href="{{ route('applicant-profile') }}" class="flex items-center border border-black px-2 py-1 bg-white hover:bg-gray-100 transition rounded w-[170px] h-[50px]">
        <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture): asset('assets/applicant/applicant-dashboard/profile_pic.png') }}" alt="User avatar" class="rounded-full w-10 h-11 mr-2" />
        <div class="text-xs font-medium">
          <div class="text-[11px]">{{ $user->first_name }} {{ $user->last_name }}</div>
          <div class="text-[12px] text-gray-600">{{ $user->role }}</div>
        </div>
      </a>

    @elseif($user->role == 'Job Provider')
      <a href="" class="flex items-center border border-black px-2 py-1 bg-white hover:bg-gray-100 transition rounded w-[170px] h-[50px]">
        <img src="{{ asset('assets/job-provider/job-provider-dashboard/profile_pic.png') }}" alt="User avatar" class="rounded-full w-10 h-11 mr-2" />
        <div class="text-xs font-medium">
          <div class="text-[11px]">{{ $user->first_name }} {{ $user->last_name }}</div>
          <div class="text-[12px] text-gray-600">{{ $user->role }}</div>
        </div>
      </a>
    @endif
    </div>