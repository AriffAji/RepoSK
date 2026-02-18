namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    public function destroy(Folder $folder)
    {
        if ($folder->parent_id === null) {
            return back()->withErrors(['error' => 'Root folder cannot be deleted']);
        }

        $this->deleteRecursive($folder);

        return back()->with('success', 'Folder deleted successfully.');
    }

    private function deleteRecursive($folder)
    {
        // delete files
        foreach ($folder->files as $file) {
            if (Storage::exists($file->path)) {
                Storage::delete($file->path);
            }
            $file->delete();
        }

        // delete children
        foreach ($folder->children as $child) {
            $this->deleteRecursive($child);
        }

        // delete directory
        if (Storage::exists($folder->path)) {
            Storage::deleteDirectory($folder->path);
        }

        $folder->delete();
    }
}
