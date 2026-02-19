<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Folder;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /* ======================================================
     | PROJECT
     ======================================================*/

    public function index()
    {
        $projects = Project::latest()->get();
        return view('projects.index', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $slug = Str::slug($request->name);

        $project = Project::create([
            'name' => $request->name,
            'slug' => $slug,
            'created_by' => $request->user()->id
        ]);

        Folder::create([
            'project_id' => $project->id,
            'parent_id'  => null,
            'name'       => $project->name,
            'path'       => "projects/{$slug}"
        ]);

        Storage::makeDirectory("projects/{$slug}");

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $folder = $project->folders()
            ->whereNull('parent_id')
            ->firstOrFail();

        $subFolders = $folder->children;

        $files = File::where('folder_id', $folder->id)
            ->where('is_latest', true)
            ->get();

        $allVersions = File::where('folder_id', $folder->id)
            ->orderBy('version')
            ->get()
            ->groupBy('base_name');

        $breadcrumb = [$folder];

        return view('projects.show', compact(
            'project',
            'folder',
            'subFolders',
            'files',
            'allVersions',
            'breadcrumb'
        ));
    }

    public function destroy(Project $project)
    {
        $rootFolder = $project->folders()->whereNull('parent_id')->first();

        if ($rootFolder) {
            $this->deleteFolderRecursive($rootFolder);
        }

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    public function monitoring()
    {
        $totalFiles = \App\Models\File::count();
        $totalProjects = \App\Models\Project::count();
        $totalDownloads = \App\Models\File::sum('download_count');
        $totalSize = \App\Models\File::sum('size');

        $totalSizeMB = round($totalSize / 1024 / 1024, 2);

        // Per project stats
        $projects = \App\Models\Project::with(['folders.files'])->get();

        foreach ($projects as $project) {
            $size = 0;
            $fileCount = 0;

            foreach ($project->folders as $folder) {
                foreach ($folder->files as $file) {
                    $size += $file->size;
                    $fileCount++;
                }
            }

            $project->total_size_mb = round($size / 1024 / 1024, 2);
            $project->file_count = $fileCount;
        }

        return view('monitoring.index', compact(
            'totalFiles',
            'totalProjects',
            'totalDownloads',
            'totalSizeMB',
            'projects'
        ));
    }


    /* ======================================================
     | FOLDER
     ======================================================*/

    public function storeFolder(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'parent_id'  => 'nullable',
            'name'       => 'required|string|max:255'
        ]);

        $parent = Folder::find($request->parent_id);

        $path = $parent
            ? $parent->path . '/' . $request->name
            : "projects/" . $request->name;

        Folder::create([
            'project_id' => $request->project_id,
            'parent_id'  => $request->parent_id,
            'name'       => $request->name,
            'path'       => $path
        ]);

        Storage::makeDirectory($path);

        return back()->with('success', 'Folder created successfully.');
    }

    public function renameFolder(Request $request, Folder $folder)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $folder->update(['name' => $request->name]);

        return back()->with('success', 'Folder renamed successfully.');
    }

    public function deleteFolder(Folder $folder)
    {
        $this->deleteFolderRecursive($folder);

        return back()->with('success', 'Folder deleted successfully.');
    }


    /* ======================================================
     | FILE
     ======================================================*/

    public function storeFile(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'folder_id'  => 'required',
            'file'       => 'required|file|max:2048'
        ]);

        $folder = Folder::findOrFail($request->folder_id);
        $file   = $request->file('file');

        $originalName = $file->getClientOriginalName();
        $extension    = $file->getClientOriginalExtension();
        $baseName     = pathinfo($originalName, PATHINFO_FILENAME);

        $latestVersion = File::where('folder_id', $folder->id)
            ->where('filename', 'LIKE', $baseName . '%')
            ->max('version');

        $version = $latestVersion ? $latestVersion + 1 : 1;

        $newName = $version > 1
            ? $baseName . '_v' . $version . '.' . $extension
            : $originalName;

        Storage::putFileAs($folder->path, $file, $newName);

        File::create([
            'folder_id'   => $folder->id,
            'filename'    => $newName,
            'stored_name' => $newName,
            'extension'   => $extension,
            'size'        => $file->getSize(),
            'path'        => $folder->path . '/' . $newName,
            'is_public'   => false
        ]);

        return redirect()->route('projects.show', $request->project_id)
            ->with('success', 'File uploaded successfully.');
    }

    public function deleteFile(File $file)
    {
        if (Storage::exists($file->path)) {
            Storage::delete($file->path);
        }

        $file->delete();

        return back()->with('success', 'File deleted successfully.');
    }

    public function downloadFile(File $file)
    {
        if (!Storage::exists($file->path)) {
            abort(404);
        }

        $file->increment('download_count');

        return Storage::download($file->path, $file->filename);
    }

    public function togglePublic(File $file)
    {
        $file->update([
            'is_public' => !$file->is_public
        ]);

        return back()->with('success', 'File visibility updated.');
    }

    public function publicDownload(File $file)
    {
        if (!$file->is_public) {
            abort(403);
        }

        if (!Storage::exists($file->path)) {
            abort(404);
        }

        $file->increment('download_count');

        return Storage::download($file->path, $file->filename);
    }


    /* ======================================================
     | PUBLIC DRIVE
     ======================================================*/

    public function publicDrive()
    {
        $projects = Project::all();
        return view('drive.index', compact('projects'));
    }

    public function publicFolder(Folder $folder)
    {
        $folders = $folder->children;
        $files   = $folder->files()->where('is_public', true)->get();

        $breadcrumb = [];
        $current = $folder;

        while ($current) {
            array_unshift($breadcrumb, $current);
            $current = $current->parent;
        }

        return view('drive.folder', compact('folder', 'folders', 'files', 'breadcrumb'));
    }

    public function publicSearch(Request $request)
    {
        $keyword = $request->q;

        $files = File::where('is_public', true)
            ->where('filename', 'like', "%{$keyword}%")
            ->get();

        $folders = Folder::where('name', 'like', "%{$keyword}%")
            ->get();

        return view('drive.search', compact('files', 'folders', 'keyword'));
    }


    /* ======================================================
     | HELPER
     ======================================================*/

    private function deleteFolderRecursive($folder)
    {
        foreach ($folder->files as $file) {
            if (Storage::exists($file->path)) {
                Storage::delete($file->path);
            }
            $file->delete();
        }

        foreach ($folder->children as $child) {
            $this->deleteFolderRecursive($child);
        }

        if (Storage::exists($folder->path)) {
            try {
                Storage::deleteDirectory($folder->path);
            } catch (\Exception $e) {
                Log::warning('Folder delete failed: ' . $e->getMessage());
            }
        }

        $folder->delete();
    }
}
