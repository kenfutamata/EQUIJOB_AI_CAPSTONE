    document.addEventListener('DOMContentLoaded', function() {
        let experienceEntryCount = initialCounts.experience;
        let educationEntryCount = initialCounts.education;

        const experienceEntriesContainer = document.getElementById('experienceEntriesContainer');
        const addExperienceBtn = document.getElementById('addExperienceBtn');

        function createExperienceEntryHTML(index, data = {}) {
            const responsibilities = data.responsibilities || '';
            return `
    <div class="experience-entry border border-gray-300 p-4 rounded-md relative">
        ${index > 0 || experienceEntriesContainer.children.length > 0 ? '<hr class="mb-4 border-t border-gray-200">' : ''}
        <button type="button" class="remove-experience-btn absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold text-xl p-1" title="Remove this experience">×</button>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="experience_${index}_employer" class="block text-lg">Employer</label>
                <input type="text" id="experience_${index}_employer" name="experience[${index}][employer]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${data.employer || ''}" />
            </div>
            <div>
                <label for="experience_${index}_jobTitle" class="block text-lg">Job Title</label>
                <input type="text" id="experience_${index}_jobTitle" name="experience[${index}][jobTitle]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${data.jobTitle || ''}" />
            </div>
            <div>
                <label for="experience_${index}_location" class="block text-lg">Location</label>
                <input type="text" id="experience_${index}_location" name="experience[${index}][location]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${data.location || ''}" />
            </div>
            <div>
                <label for="experience_${index}_year" class="block text-lg">Year</label>
                <input type="text" id="experience_${index}_year" name="experience[${index}][year]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${data.year || ''}" />
            </div>
            <div class="md:col-span-2">
                <label for="experience_${index}_responsibilities" class="block text-lg">Responsibilities/Description</label>
                <textarea id="experience_${index}_responsibilities" name="experience[${index}][responsibilities]" class="w-full border border-black bg-gray-300 h-24 resize-none p-2" placeholder="Describe your key responsibilities and achievements...">${responsibilities}</textarea>
            </div>
        </div>
    </div>
    `;
        }

        function addExperienceEntry(isInitial = false) {
            if (isInitial && experienceEntriesContainer.children.length > 0) {
                return;
            }
            const index = experienceEntryCount++;
            const entryHTML = createExperienceEntryHTML(index);
            experienceEntriesContainer.insertAdjacentHTML('beforeend', entryHTML);

            if (!isInitial && experienceEntriesContainer.lastElementChild) {
                experienceEntriesContainer.lastElementChild.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }

        addExperienceBtn.addEventListener('click', () => {
            addExperienceEntry(false);
        });

        experienceEntriesContainer.addEventListener('click', (event) => {
            if (event.target.classList.contains('remove-experience-btn')) {
                event.target.closest('.experience-entry')?.remove();
            }
        });

        const educationEntriesContainer = document.getElementById('educationEntriesContainer');
        const addEducationBtn = document.getElementById('addEducationBtn');

        function createEducationEntryHTML(index, data = {}) {
            return `
    <div class="education-entry border border-gray-300 p-4 rounded-md relative">
        ${index > 0 || educationEntriesContainer.children.length > 0 ? '<hr class="mb-4 border-t border-gray-200">' : ''}
        <button type="button" class="remove-education-btn absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold text-xl p-1" title="Remove this education">×</button>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="education_${index}_school" class="block text-lg">School</label>
                <input type="text" id="education_${index}_school" name="educations[${index}][school]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${data.school || ''}" />
            </div>
            <div>
                <label for="education_${index}_degree" class="block text-lg">Degree</label>
                <input type="text" id="education_${index}_degree" name="educations[${index}][degree]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${data.degree || ''}" />
            </div>
            <div>
                <label for="education_${index}_location" class="block text-lg">Location</label>
                <input type="text" id="education_${index}_location" name="educations[${index}][location]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${data.location || ''}" />
            </div>
            <div>
                <label for="education_${index}_year" class="block text-lg">Year Graduated</label>
                <input type="date" id="education_${index}_year" name="educations[${index}][year]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${data.year || ''}" />
            </div>
            <div class="md:col-span-2">
                <label for="education_${index}_description" class="block text-lg">Description</label>
                <textarea id="education_${index}_description" name="educations[${index}][description]" class="w-full border border-black bg-gray-300 h-24 resize-none p-2" placeholder="Details about your studies, honors, or relevant info...">${data.description || ''}</textarea>
            </div>
        </div>
    </div>
    `;
        }

        function addEducationEntry(isInitial = false) {
            if (isInitial && educationEntriesContainer.children.length > 0) {
                return;
            }
            const index = educationEntryCount++;
            const entryHTML = createEducationEntryHTML(index);
            educationEntriesContainer.insertAdjacentHTML('beforeend', entryHTML);

            if (!isInitial && educationEntriesContainer.lastElementChild) {
                educationEntriesContainer.lastElementChild.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }

        addEducationBtn.addEventListener('click', () => {
            addEducationEntry(false);
        });

        educationEntriesContainer.addEventListener('click', (event) => {
            if (event.target.classList.contains('remove-education-btn')) {
                event.target.closest('.education-entry')?.remove();
            }
        });
    });