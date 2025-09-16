<div class="w-[234px] h-screen bg-[#B3C7F7] flex flex-col justify-between border-r border-black p-2">
    <div>
        <div class="text-center mb-6">
            <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/equijob_logo_dashboard.png') }}" alt="Logo" class="mx-auto" />
        </div>
        <nav class="flex flex-col gap-2 px-2">
            <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="{{ route('applicant-dashboard') }}">
                <img src="{{ asset('assets/photos/dashboard/job-provider-dashboard/Dashboard.png') }}" alt="Dashboard Icon" />
                <span>Dashboard</span>
            </a>
            <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="{{ route('applicant-resume-builder') }}">
                <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/resume.png') }}" alt="Resume Icon" />
                <span>Resume Builder</span>
            </a>
            <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="{{ route('applicant-match-jobs') }}">
                <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/ai_job_matching.png') }}" alt="Job Matching Icon" />
                <span>AI Job Matching</span>
            </a>
            <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="{{ route('applicant-job-applications') }}">
                <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/job_applications.png') }}" alt="Application Management" />
                <span>Application Management</span>
            </a>
            <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="{{route('applicant-application-tracker')}}">
                <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/job_collections.png') }}" alt="collections Icon" />
                <span>Job Collections</span>
            </a>
            <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="{{route('applicant-application-tracker')}}">
                <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/applicant_tracker.png') }}" alt="Tracker Icon" />
                <span>Applicant Tracker</span>
            </a>
            <a class="flex items-center gap-3 px-2 py-2 rounded bg-[#B3C7F7] text-[#262626] font-semibold hover:bg-[#a4b8e0]" href="{{route('applicant-feedback')}}">
                <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/applicant_feedback.png') }}" alt="Feedback Icon" />
                <span>Applicant Feedback</span>
            </a>
        </nav>
    </div>

    <form method="GET" action="{{ route('applicant-logout') }}">
        @csrf
        <button class="flex items-center gap-3 px-4 py-2 text-[#F13E3E] font-medium hover:bg-white-100 w-full rounded">
            <img src="{{ asset('assets/photos/dashboard/logout.png') }}" alt="Logout Icon" />
            <span>Log-out</span>
        </button>
    </form>
</div>