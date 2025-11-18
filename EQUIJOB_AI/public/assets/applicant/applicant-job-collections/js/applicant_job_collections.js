const SUPABASE_BASE_URL = "https://zlusioxytbqhxohsfvyr.supabase.co/storage/v1/object/public/equijob_storage";

function openJobDetailsModal(button) {
    const jobposting = JSON.parse(button.getAttribute('data-jobposting'));
    const addressParts = [
        jobposting.companyAddress,
        jobposting.cityName,
        jobposting.provinceName
    ].filter(Boolean);
    document.getElementById('modal-position').textContent = jobposting.position || 'No Position provided';
    document.getElementById('modal-companyName').textContent = jobposting.companyName || 'No Company provided';
    document.getElementById('modal-disabilityType').textContent = jobposting.disabilityType || 'No Disability Type provided';
    document.getElementById('modal-educationalAttainment').textContent = jobposting.educationalAttainment || 'No Educational Attainment provided';
    document.getElementById('modal-workEnvironment').textContent = jobposting.workEnvironment || 'No Work Environment provided';
    document.getElementById('modal-skills').textContent = jobposting.skills || 'No Skills provided';
    document.getElementById('modal-requirements').textContent = jobposting.requirements || 'No Requirements provided';
    document.getElementById('modal-contactPhone').textContent = jobposting.contactPhone || 'No phone number provided';
    document.getElementById('modal-contactEmail').textContent = jobposting.contactEmail || 'No contact email provided';
    document.getElementById('modal-description').textContent = jobposting.description || 'No description provided';
    document.getElementById('modal-salaryRange').textContent = jobposting.salaryRange || 'No Salary Range provided';
    document.getElementById('modal-category').textContent = jobposting.category || 'No Category provided';
    document.getElementById('modal-companyAddress').textContent = addressParts.length ? addressParts.join(', ') : 'No Address provided';
    const logo = document.getElementById('modal-companyLogo');
    logo.src = jobposting.companyLogo || '';
    logo.style.display = jobposting.companyLogo ? 'block' : 'none';

    document.getElementById('viewJobDetailsModal').classList.remove('hidden');
}

function closeViewJobDetailsModal() {
    document.getElementById('viewJobDetailsModal').classList.add('hidden');
}



document.addEventListener('DOMContentLoaded', function () {
    const provinceSelect = document.getElementById('province-select');
    const citySelect = document.getElementById('city-select');
    const cityHelperText = document.getElementById('city-helper-text');

    if (provinceSelect && citySelect && cityHelperText) {

        provinceSelect.addEventListener('change', function () {
            const provinceId = this.value;

            citySelect.innerHTML = '<option value="">All Cities</option>';

            if (!provinceId) {
                citySelect.disabled = true;
                cityHelperText.style.display = 'block';
            }

            fetch(`/get-cities/${provinceId}`)
                .then(response => response.json())
                .then(cities => {
                    if (cities.length > 0) {
                        cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.id;
                            option.textContent = city.cityName;
                            citySelect.appendChild(option);
                        });
                        citySelect.disabled = false;
                        cityHelperText.style.display = 'none';
                    } else {
                        citySelect.disabled = true;
                        cityHelperText.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error fetching cities:', error);
                    citySelect.disabled = true;
                    cityHelperText.style.display = 'block';
                });
        });
    }
});