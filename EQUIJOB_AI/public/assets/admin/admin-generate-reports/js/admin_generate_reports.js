document.addEventListener('DOMContentLoaded', () => {

    const notifBar = document.getElementById('notification-bar');
    if (notifBar) {
        setTimeout(() => {
            notifBar.style.opacity = '0';
            setTimeout(() => notifBar.remove(), 500);
        }, 3000);
    }

    @if($applicantChartData)
    const applicantCtx = document.getElementById('applicantRegisteredChart').getContext('2d');
    const applicantData = @json($applicantChartData);
    new Chart(applicantCtx, {
        type: 'line',
        data: {
            labels: applicantData.labels,
            datasets: [{
                label: 'New Applicants Registered',
                data: applicantData.values,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: true,
                tension: 0.1
            }]
        },
        options: {}
    });
    @endif

    @if($jobProviderChartData)
    const providerCtx = document.getElementById('jobProviderChart').getContext('2d');
    const providerData = @json($jobProviderChartData);
    new Chart(providerCtx, {
        type: 'line',
        data: {
            labels: providerData.labels,
            datasets: [{
                label: 'New Job Providers Registered',
                data: providerData.values,
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                fill: true,
                tension: 0.1
            }]
        },
        options: {
            /* ... your chart options ... */
        }
    });
    @endif

    // --- Chart 3: Hired Applicants ---
    @if($hiredChartData)
    const hiredCtx = document.getElementById('hiredApplicantsChart').getContext('2d');
    const hiredData = @json($hiredChartData);
    new Chart(hiredCtx, {
        type: 'bar',

        data: {
            labels: hiredData.labels,
            datasets: [{
                label: 'Applicants Hired',
                data: hiredData.values,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {}
    });
    @endif

    @if($disapprovedChartData)
    const rejectedCtx = document.getElementById('rejectedApplicantsChart').getContext('2d');
    const rejectedData = @json($disapprovedChartData);
    new Chart(rejectedCtx, {
        type: 'bar',
        data: {
            labels: rejectedData.labels,
            datasets: [{
                label: 'Applicants Rejected/Disapproved',
                data: rejectedData.values,
                backgroundColor: 'rgba(255, 0, 0, 0.6)',
                borderColor: 'rgba(255, 0, 0, 1)',
                borderWidth: 1
            }]
        },
        options: {
        }
    });
    @endif

});