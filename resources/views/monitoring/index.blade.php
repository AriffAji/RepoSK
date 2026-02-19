{{-- <x-app-layout>
    <div class="max-w-6xl mx-auto mt-10">

        <h2 class="text-2xl font-bold mb-8">
            Resource Monitoring Dashboard
        </h2>

        <div class="grid grid-cols-4 gap-6">

            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-gray-500 text-sm">Total Projects</h3>
                <p class="text-2xl font-bold">{{ $totalProjects }}</p>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-gray-500 text-sm">Total Files</h3>
                <p class="text-2xl font-bold">{{ $totalFiles }}</p>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-gray-500 text-sm">Total Downloads</h3>
                <p class="text-2xl font-bold">{{ $totalDownloads }}</p>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-gray-500 text-sm">Total Size (MB)</h3>
                <p class="text-2xl font-bold">{{ $totalSizeMB }} MB</p>
            </div>

        </div>

        <div class="mt-12">
            <h3 class="text-xl font-bold mb-6">Storage Per Project</h3>

            <div class="space-y-6">
                @foreach ($projects as $project)
                    <div class="bg-white p-6 rounded shadow">

                        <div class="flex justify-between mb-2">
                            <span class="font-semibold">
                                {{ $project->name }}
                            </span>
                            <span class="text-sm text-gray-500">
                                {{ $project->total_size_mb }} MB • {{ $project->file_count }} files
                            </span>
                        </div>

                        @php
                            $percentage = $totalSizeMB > 0
                                ? ($project->total_size_mb / $totalSizeMB) * 100
                                : 0;
                        @endphp

                        <div class="w-full bg-gray-200 rounded h-3">
                            <div class="bg-blue-600 h-3 rounded"
                                style="width: {{ $percentage }}%">
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

    </div>
</x-app-layout>
 --}}
<x-app-layout>

    <div class="max-w-7xl mx-auto py-12 px-4">

        {{-- Header --}}
        <div class="mb-10" data-aos="fade-down">
            <h2 class="text-3xl font-bold text-gray-800">
                Resource Monitoring Dashboard
            </h2>
            <p class="text-gray-500 mt-2">
                Overview of storage, files, and activity across projects
            </p>
        </div>

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <div data-aos="fade-up"
                class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300">
                <p class="text-gray-500 text-sm">Total Projects</p>
                <p class="text-3xl font-bold mt-2 text-blue-600">
                    {{ $totalProjects }}
                </p>
            </div>

            <div data-aos="fade-up" data-aos-delay="100"
                class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300">
                <p class="text-gray-500 text-sm">Total Files</p>
                <p class="text-3xl font-bold mt-2 text-indigo-600">
                    {{ $totalFiles }}
                </p>
            </div>

            <div data-aos="fade-up" data-aos-delay="200"
                class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300">
                <p class="text-gray-500 text-sm">Total Downloads</p>
                <p class="text-3xl font-bold mt-2 text-green-600">
                    {{ $totalDownloads }}
                </p>
            </div>

            <div data-aos="fade-up" data-aos-delay="300"
                class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300">
                <p class="text-gray-500 text-sm">Total Size (MB)</p>
                <p class="text-3xl font-bold mt-2 text-purple-600">
                    {{ $totalSizeMB }} MB
                </p>
            </div>

        </div>


        {{-- STORAGE PER PROJECT --}}
        <div class="mt-14">

            <div class="flex justify-between items-center mb-6" data-aos="fade-right">
                <h3 class="text-2xl font-semibold text-gray-800">
                    Storage Per Project
                </h3>
            </div>

            <div class="space-y-6">

                @foreach ($projects as $project)
                    @php
                        $percentage = $totalSizeMB > 0 ? ($project->total_size_mb / $totalSizeMB) * 100 : 0;
                    @endphp

                    <div data-aos="fade-up"
                        class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300">

                        {{-- Header --}}
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="font-semibold text-gray-800 text-lg">
                                    {{ $project->name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $project->file_count }} files
                                </p>
                            </div>

                            <div class="text-sm text-gray-500">
                                {{ $project->total_size_mb }} MB
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="h-3 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all duration-700"
                                style="width: {{ $percentage }}%">
                            </div>
                        </div>

                        {{-- Percentage Label --}}
                        <div class="text-right mt-2 text-xs text-gray-400">
                            {{ number_format($percentage, 1) }}% of total storage
                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </div>

    {{-- AOS Init --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            AOS.init({
                duration: 800,
                once: true
            });
        });
    </script>

</x-app-layout>
