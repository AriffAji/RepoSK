<x-app-layout>
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
                @foreach($projects as $project)
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

