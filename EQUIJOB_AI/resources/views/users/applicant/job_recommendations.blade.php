    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://cdn.tailwindcss.com"></script>
        <title>EQUIJOB - Job Recommendations</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Epilogue:wght@400;600;700&family=Inter:wght@400&display=swap" rel="stylesheet">
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Epilogue', 'sans-serif'],
                            inter: ['Inter', 'sans-serif'],
                            audiowide: ['Audiowide', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
    </head>

    <body class="bg-[#FCFDFF] text-gray-800 font-sans antialiased min-h-screen flex">

        <!-- Sidebar -->
        <aside class="w-[234px] bg-white hidden lg:block h-screen fixed top-0 left-0">
            <x-applicant-sidebar />
        </aside>

        <div class="flex flex-col flex-1 w-full lg:ml-[234px] min-h-screen">

            <div class="fixed top-0 left-[234px] right-0 h-16 z-30 bg-white border-b border-gray-200">
                <x-topbar :user="$user" :notifications="$notifications" :unreadNotifications="$unreadNotifications" />
            </div>

            <main class="p-4 sm:p-6 lg:p-10 pt-20 lg:pt-24 flex-1">
                <div class="mb-8">
                    <h1 class="font-audiowide text-3xl md:text-4xl text-gray-800">
                        <span class="text-[#25324B]">AI Job </span>
                        <span class="text-[#26A4FF]">Matching</span>
                    </h1>
                    <p class="text-gray-500 mt-2">Personalized job recommendations just for you, based on your resume.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6">

                    @forelse($recommendedJobs as $job)
                    <article class="bg-white border border-gray-200 rounded-lg p-6 flex flex-col gap-4 hover:shadow-lg hover:border-blue-500 transition-all duration-300">
                        <header class="flex justify-between items-start gap-4">
                            @if($job->companyLogo)
                            <img class="w-12 h-12 rounded-lg object-contain border border-gray-100" src="{{ asset('storage/' . $job->companyLogo) }}" alt="{{ $job->companyName }} logo" />
                            @else
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-blue-500 font-bold text-lg">
                                {{ substr($job->companyName, 0, 1) }}
                            </div>
                            @endif
                            <button onclick="openViewJobPostingModal(this)" data-jobposting='@json($job)' class="text-center text-sm font-semibold text-blue-600 border border-blue-600 px-4 py-1.5 rounded-full whitespace-nowrap hover:bg-blue-600 hover:text-white transition-colors duration-200">
                                View Job Posting
                            </button>
                            <a href="#" class="text-center text-sm font-semibold text-blue-600 border border-blue-600 px-4 py-1.5 rounded-full whitespace-nowrap hover:bg-blue-600 hover:text-white transition-colors duration-200">
                                Apply Now
                            </a>
                        </header>
                        <div class="flex flex-col gap-2">
                            <div>

                                <h3 class="text-lg font-bold text-[#25324B]">{{ $job->position }}</h3>
                                <div class="flex items-center gap-2 text-sm text-gray-500 mt-1 flex-wrap">
                                    <span>{{ $job->companyName }}</span>
                                </div>
                            </div>

                        </div>
                        <p class="text-gray-600 font-inter text-sm line-clamp-2">
                            {{ $job->description }}
                        </p>
                        <footer class="mt-auto pt-2">
                            {{-- Corrected the property name from disability_type to disabilityType --}}
                            @if($job->disabilityType && $job->disabilityType !== 'Any' && $job->disabilityType !== 'Not Specified')
                            <span class="text-sm font-semibold text-yellow-800 bg-yellow-100 px-3 py-1 rounded-full">
                                {{ $job->disabilityType }}
                            </span>
                            @endif
                        </footer>
                    </article>
                    @empty
                    <div class="col-span-full bg-blue-50 border border-blue-200 text-blue-800 p-8 rounded-lg text-center">
                        <h3 class="text-xl font-bold">No Matching Jobs Found</h3>
                        <p class="mt-2">We couldn't find any jobs that strongly match your current resume.</p>
                        <p class="mt-1">Consider updating your skills in the Resume Builder for better results.</p>
                        <a href="{{ route('applicant-resume-builder') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                            Go to Resume Builder
                        </a>
                    </div>
                    @endforelse

                </div>
            </main>

        </div>

        <div id="viewJobPostingModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center hidden">
            <!-- Modal Container -->
            <div class="relative bg-white rounded-lg w-full max-w-6xl mx-4 overflow-auto max-h-[90vh] p-8 flex flex-col gap-6">

                <!-- Close Button -->
                <button onclick="closeViewJobPostingModal()" class="absolute top-4 right-4 text-gray-600 hover:text-black text-xl">&times;</button>

                <!-- Header Section -->
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="flex-shrink-0">
                        <img id="modal-companyLogo" src="https://placehold.co/180x151" alt="Company Logo" class="w-44 h-auto border" />
                    </div>
                    <div>
                        <h2 id="modal-companyName" class="text-2xl font-semibold text-gray-800">Company Name</h2>
                        <p id="modal-position" class="text-xl text-gray-700 mt-1">Position</p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-6">
                    <div class="border w-full md:max-w-xs p-4 flex flex-col gap-4">
                        <h3 class="text-lg font-semibold border-b pb-1">Job Information</h3>
                        <div class="flex items-start gap-3">
                            <img src="https://placehold.co/30x30" alt="Icon" class="w-6 h-6" />
                            <div>
                                <p class="text-sm text-gray-700">Disability Type</p>
                                <p id="modal-disabilityType" class="text-sm text-blue-600 font-medium"></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <img src="https://placehold.co/30x30" alt="Icon" class="w-6 h-6" />
                            <div>
                                <p class="text-sm text-gray-700">Salary</p>
                                <p id="modal-salaryRange" class="text-sm text-blue-600 font-medium">₱ 0</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col gap-6">
                        <div class="border p-4">
                            <h3 class="text-lg font-semibold border-b pb-1 mb-2">Job Description</h3>
                            <p id="modal-description" class="text-sm text-gray-700 leading-relaxed"></p>
                        </div>
                        <div class="border p-4">
                            <h3 class="text-lg font-semibold border-b pb-1 mb-2">Educational Attainment</h3>
                            <p id="modal-educationalAttainment" class="text-sm text-gray-700 leading-relaxed"></p>
                        </div>
                        <div class="border p-4">
                            <h3 class="text-lg font-semibold border-b pb-1 mb-2">Skills</h3>
                            <p id="modal-skills" class="text-sm text-gray-700 leading-relaxed"></p>
                        </div>
                        <div class="border p-4">
                            <h3 class="text-lg font-semibold border-b pb-1 mb-2">Requirements</h3>
                            <p id="modal-requirements" class="text-sm text-gray-700 leading-relaxed"></p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button class="px-5 py-2 border border-indigo-600 text-indigo-600 hover:bg-indigo-50 rounded transition">
                        Apply Now
                    </button>
                </div>
            </div>
        </div>

        <script>
            function openViewJobPostingModal(button) {
                const job = JSON.parse(button.getAttribute('data-jobposting'));

                document.getElementById('modal-position').textContent = job.position || 'N/A';
                document.getElementById('modal-companyName').textContent = job.companyName || 'N/A';
                document.getElementById('modal-disabilityType').textContent = job.disabilityType || 'Not Specified';
                document.getElementById('modal-salaryRange').textContent = job.salaryRange || '₱ 0';
                document.getElementById('modal-educationalAttainment').textContent = job.educationalAttainment || 'No educational attainment provided.';
                document.getElementById('modal-skills').textContent = job.skills || 'No skills required.';
                document.getElementById('modal-description').textContent = job.description || 'No description provided.';
                document.getElementById('modal-requirements').textContent = job.requirements || 'No requirements provided.';

                const logo = document.getElementById('modal-companyLogo');
                if (job.companyLogo) {
                    logo.src = `/storage/${job.companyLogo}`;
                    logo.style.display = 'block';
                } else {
                    logo.src = 'https://placehold.co/180x151';
                }

                document.getElementById('viewJobPostingModal').classList.remove('hidden');
            }
            function closeViewJobPostingModal() {
                document.getElementById('viewJobPostingModal').classList.add('hidden');
            }
        </script>
    </body>

    </html>