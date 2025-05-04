<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script src="https://cdn.tailwindcss.com"></script>
<div class="sidebar">
  <div class="sidebar-top">
    <div class="logo">
      <img src="{{ asset('assets/photos/dashboard/job-provider-dashboard/equijob_logo_dashboard.png') }}" alt="Logo" />
    </div>
    <nav class="nav">
      <a class="nav-item" href="#">
        <img src="{{ asset('assets/photos/dashboard/job-provider-dashboard/Dashboard.png') }}" alt="Dashboard Icon" />
        <span>Dashboard</span>
      </a>
      <a class="nav-item" href="#">
        <img src="{{ asset('assets/photos/dashboard/job-provider-dashboard/job_applicant.png') }}" alt="Job Application Icon" />
        <span>Job Matching</span>
      </a>
      <a class="nav-item" href="#">
        <img src="{{ asset('assets/photos/dashboard/job-provider-dashboard/job_posting.png') }}" alt="Job Posting Icon" />
        <span>Job Posting</span>
      </a>
      <a class="nav-item" href="#">
        <img src="{{ asset('assets/photos/dashboard/job-provider-dashboard/generate_monthly_report.png') }}" alt="Monthly Report Icon" />
        <span>Monthly Report</span>
      </a>
      <a class="nav-item" href="#">
        <img src="{{ asset('assets/photos/dashboard/job-provider-dashboard/applicant_feedback.png') }}" alt="Feedback Icon" />
        <span>Applicant Feedback</span>
      </a>
    </nav>
  </div>

  <a class="nav-item" href="#">
      <img src="{{ asset('assets/photos/dashboard/logout.png') }}" alt="Logout" />
      <span>Log-out</span>
  </a>
</div>

<style scoped>
.sidebar {
  width: 234px;
  height: 100vh;
  background-color: #B3C7F7;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  border-right: 1px solid black;
  padding: 10px 0;
}

.sidebar-top {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.logo {
  text-align: center;
}

.nav {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding: 0 10px;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 8px 4px;
  border-radius: 5px;
  text-decoration: none;
  color: #262626;
  font-weight: 600;
  background: #B3C7F7;
  transition: background 0.3s ease;
}

.nav-item:hover {
  background-color: #a4b8e0;
}

.logout {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px;
  color: #F13E3E;
  font-weight: 500;
  cursor: pointer;
}

.logout:hover {
  background-color: #fdd;
}

@media (max-width: 768px) {
  .sidebar {
    width: 100%;
    height: auto;
    flex-direction: row;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-around;
    border-right: none;
    border-bottom: 1px solid black;
  }

  .nav {
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
  }

  .nav-item {
    flex: 1 1 120px;
    justify-content: center;
  }

  .logout {
    justify-content: center;
    width: 100%;
  }
}
</style>