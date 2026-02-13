<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Folder;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
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

        // buat root folder di database
        $rootFolder = Folder::create([
            'project_id' => $project->id,
            'parent_id' => null,
            'name' => $project->name,
            'path' => "projects/{$slug}"
        ]);


        // buat folder fisik di storage
        Storage::makeDirectory("projects/{$slug}");

        return redirect()->route('projects.index');

    }

    public function index()
    {
        $projects = Project::latest()->get();

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $rootFolder = $project->folders()
            ->whereNull('parent_id')
            ->with('files')
            ->first();

        return view('projects.show', compact('project', 'rootFolder'));
    }

    public function storeFolder(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'parent_id' => 'nullable',
            'name' => 'required'
        ]);

        $parentFolder = Folder::find($request->parent_id);

        $path = $parentFolder
            ? $parentFolder->path . '/' . $request->name
            : "projects/" . $request->name;

        $folder = Folder::create([
            'project_id' => $request->project_id,
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'path' => $path
        ]);

        Storage::makeDirectory($path);

        return back();
    }

    public function storeFile(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'folder_id' => 'required',
            'file' => 'required|file|max:2048' // 2048 KB = 2MB
        ]);

        $folder = Folder::findOrFail($request->folder_id);

        $file = $request->file('file');

        $originalName = $file->getClientOriginalName();


        Storage::makeDirectory($folder->path);

        Storage::putFileAs(
            $folder->path,
            $file,
            $originalName
        );

        if (Storage::exists($folder->path . '/' . $originalName)) {
            return back()->withErrors([
                'file' => 'File dengan nama yang sama sudah ada.'
            ]);
        }

        \App\Models\File::create([
            'folder_id' => $folder->id,
            'filename' => $file->getClientOriginalName(),
            'stored_name' => $originalName,
            'extension' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
            'path' => $folder->path . '/' . $originalName,
            'is_public' => false
        ]);

        return redirect()->route('projects.show', $request->project_id);
    }

    public function downloadFile(\App\Models\File $file)
    {
        if (!Storage::exists($file->path)) {
            abort(404);
        }

        $file->increment('download_count');

        return Storage::download($file->path, $file->filename);
    }


    public function togglePublic(\App\Models\File $file)
    {
        $file->update([
            'is_public' => !$file->is_public
        ]);

        return back();
    }

    public function publicDownload(\App\Models\File $file)
    {
        if (!$file->is_public) {
            abort(403);
        }

        if (!Storage::exists($file->path)) {
            abort(404);
        }

        $file->increment('download_count'); // WAJIB ADA

        return Storage::download($file->path, $file->filename);
    }



    public function publicDrive()
    {
        $projects = \App\Models\Project::all();

        return view('drive.index', compact('projects'));
    }

    public function publicFolder(\App\Models\Folder $folder)
    {
        $folders = $folder->children;
        $files = $folder->files()->where('is_public', true)->get();

        // generate breadcrumb
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

        $files = \App\Models\File::where('is_public', true)
            ->where('filename', 'like', "%{$keyword}%")
            ->get();

        $folders = \App\Models\Folder::where('name', 'like', "%{$keyword}%")
            ->get();

        return view('drive.search', compact('files', 'folders', 'keyword'));
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

    public function showFolder(\App\Models\Folder $folder)
    {
        $subFolders = $folder->children;
        $files = $folder->files;

        // Generate breadcrumb
        $breadcrumb = [];
        $current = $folder;

        while ($current) {
            array_unshift($breadcrumb, $current);
            $current = $current->parent;
        }

        return view('projects.folder', compact(
            'folder',
            'subFolders',
            'files',
            'breadcrumb'
        ));
    }

    public function renameFolder(Request $request, \App\Models\Folder $folder)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $folder->update([
            'name' => $request->name
        ]);

        return back();
    }

    public function deleteFolder(\App\Models\Folder $folder)
    {
        \Illuminate\Support\Facades\Storage::deleteDirectory($folder->path);

        $folder->delete();

        return back();
    }


    public function deleteFile(\App\Models\File $file)
    {
        \Illuminate\Support\Facades\Storage::delete($file->path);

        $file->delete();

        return back();
    }



}