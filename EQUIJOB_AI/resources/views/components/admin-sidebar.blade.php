<div class="w-[234px] min-h-screen bg-[#B3C7F7] flex flex-col justify-between border-r border-black p-2">
  <div>
    <div class="text-center mb-6">
      <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/equijob_logo_dashboard.png') }}" alt="Logo" class="mx-auto" />
    </div>
    <nav class="flex flex-col gap-2 px-2">
      <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="{{ route('admin-dashboard') }}">
        <img src="{{ asset('assets/photos/dashboard/job-provider-dashboard/Dashboard.png') }}" alt="Dashboard Icon" />
        <span>Dashboard</span>
      </a>
      <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="{{ route('admin-manage-user-applicants') }}">
        <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/ai_job_matching.png') }}" alt="Manage Users" />
        <span>Manage User</span>
      </a>
      <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="{{route('admin-manage-job-applications')}}">
        <img src="{{ asset('assets/photos/dashboard/admin-dashboard/job_applicant.png') }}" alt="Job Posting Icon" />
        <span>Manage Job Applications</span>
      </a>
      <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="{{route('admin-manage-job-posting')}}">
        <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/job_posting.png') }}" alt="Job Posting Icon" />
        <span>Manage Job Posting</span>
      </a>
      <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="{{route('admin-generate-report')}}">
        <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/generate_monthly_report.png') }}" alt="Report Icon" />
        <span>Monthly Report</span>
      </a>
      <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="{{route('admin-feedback-contact-us-system-review')}}">
        <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/applicant_feedback.png') }}" alt="Feedback Icon" />
        <span>Feedback</span>
      </a>
    </nav>
  </div>

  <form method="GET" action="{{ route('admin-logout') }}">
    @csrf
    <button class="flex items-center gap-3 px-4 py-2 text-[#F13E3E] font-medium hover:bg-white-100 w-full rounded">
      <img src="{{ asset('assets/photos/dashboard/logout.png') }}" alt="Logout Icon" />
      <span>Log-out</span>
    </button>
  </form>
</div>