<div class="w-[234px] min-h-screen bg-[#B3C7F7] flex flex-col justify-between border-r border-black p-2">
  <div>
    <div class="text-center mb-6">
      <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/equijob_logo_dashboard.png') }}" alt="Logo" class="mx-auto" />
    </div>
    <nav class="flex flex-col gap-2 px-2">
      <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="#">
        <img src="{{ asset('assets/photos/dashboard/job-provider-dashboard/Dashboard.png') }}" alt="Dashboard Icon" />
        <span>Dashboard</span>
      </a>
      <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="#">
        <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/job_applicant.png') }}" alt="Job Matching Icon" />
        <span>Job Matching</span>
      </a>
      <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="#">
        <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/job_posting.png') }}" alt="Job Posting Icon" />
        <span>Job Posting</span>
      </a>
      <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="#">
        <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/generate_monthly_report.png') }}" alt="Report Icon" />
        <span>Monthly Report</span>
      </a>
      <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="#">
        <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/applicant_feedback.png') }}" alt="Feedback Icon" />
        <span>Applicant Feedback</span>
      </a>
    </nav>
  </div>

  <form method="GET" action="{{ route('applicant-logout') }}">
    @csrf
    <button class="flex items-center gap-3 px-4 py-2 text-[#F13E3E] font-medium hover:bg-red-100 w-full rounded">
      <img src="{{ asset('assets/photos/dashboard/logout.png') }}" alt="Logout Icon" />
      <span>Log-out</span>
    </button>
  </form>
</div>
