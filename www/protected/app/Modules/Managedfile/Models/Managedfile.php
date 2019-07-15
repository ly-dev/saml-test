<?php
namespace App\Modules\Managedfile\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Managedfile extends Model
{

    const STORAGE_PUBLIC = 'public';

    const STORAGE_PRIVATE = 'private';

    /**
     *
     * @var string
     */
    protected $table = 'managed_files';

    /**
     *
     * @var array
     */
    protected $fillable = [
        'file_name',
        'description',
        'mime_type',
        'file_size',
        'file_uri'
    ];

    protected $hidden = [
        'data_blob'
    ];

    public function getAssetPathAttribute()
    {
        $storage = $this->getStorageFromUri();
        $disk = Storage::disk($storage);
        $path = $this->getPathFromUri($storage);

        return $disk->getAdapter()->getPathPrefix() . $path;
    }

    public function showFile($download = false)
    {
        header("Content-type: {$this->mime_type}");
        header("Content-Length: {$this->file_size}");

        if ($download) {
            header('Content-Disposition: attachment; filename="' . $this->file_name . '"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');

            // check for IE only headers
            if (preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) || (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false)) {
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
            } else {
                header('Pragma: no-cache');
            }
        }

        $handle = fopen($this->assetPath, 'rb');
        fpassthru($handle);
        fclose($handle);
    }

    public function getAssetUrlAttribute()
    {
        $url = null;
        $storage = $this->getStorageFromUri();

        switch ($storage) {
            case self::STORAGE_PUBLIC:
                $path = $this->getPathFromUri($storage);
                $url = asset('assets/uploads/' . $path);
                break;
            case self::STORAGE_PRIVATE:
                $url = url('managed-file/private/' . $this->id);
                break;
        }

        return $url;
    }

    public function getDownloadUrlAttribute()
    {
        $url = $this->getAssetUrlAttribute();
        if (! empty($url)) {
            $url = $url . '?download=1';
        }

        return $url;
    }

    private function getPathFromUri($storage = null)
    {
        $uri = $this->file_uri;
        if (! empty($uri)) {
            if (empty($storage)) {
                $storage = $this->getStorageFromUri();
            }
            $prefix = $this->generateUriPrefix($storage);
            $path = substr($uri, strlen($prefix));
            return $path;
        }

        abort(404, "Path can't be determined from uri {$uri}");
    }

    private function getStorageFromUri()
    {
        $uri = $this->file_uri;
        if (! empty($uri)) {
            foreach ([
                self::STORAGE_PUBLIC,
                self::STORAGE_PRIVATE
            ] as $s) {
                $prefix = $this->generateUriPrefix($s);
                if (strpos($uri, $prefix) === 0) {
                    return $s;
                }
            }
        }

        abort(404, "Storage can't be determined from uri {$uri}");
    }

    private function generateUriPrefix($storage)
    {
        if ($storage != self::STORAGE_PUBLIC && $storage != self::STORAGE_PRIVATE) {
            abort(404, "Storage {$storage} not found");
        }
        return $storage . '://';
    }

    /**
     * Save managed file instance from request
     *
     * @param Request $request
     * @param string $inputName
     * @param string $storage,
     *            'public' or 'private, default 'public',
     * @param string $subfolder,
     *            subfolder
     * @return Managedfile
     */
    static public function saveInstanceFromRequest(Request $request, $inputName, $storage = self::STORAGE_PUBLIC, $subfolder = '')
    {
        $model = self::createInstanceFromRequest($request, $inputName, $storage, $subfolder);
        if (isset($model)) {
            $model->save();
        }

        return $model;
    }

    /**
     * Create managed file instance from request
     *
     * @param Request $request
     * @param string $inputName
     * @param string $storage,
     *            'public' or 'private, default 'public',
     * @param string $subfolder,
     *            subfolder
     * @return Managedfile
     */
    static public function createInstanceFromRequest(Request $request, $inputName, $storage = self::STORAGE_PUBLIC, $subfolder = '')
    {
        $model = null;

        if ($request->hasFile($inputName)) {
            $uploadedFile = $request->file($inputName);
            if ($uploadedFile->isValid()) {
                $filename = uniqid('mf_', true) . '.' . $uploadedFile->getClientOriginalExtension();
                $destinationPath = (empty($subfolder) ? '' : $subfolder . '/') . $filename;
                // @TODO replace put with move
                Storage::disk($storage)->put($destinationPath, file_get_contents($uploadedFile->getRealPath()));

                $model = new Managedfile();
                $model->file_name = $uploadedFile->getClientOriginalName();
                $model->description = 'uploaded by ' . $request->getPathInfo();
                $model->mime_type = $uploadedFile->getMimeType();
                $model->file_size = Storage::disk($storage)->size($destinationPath);
                $model->file_uri = $model->generateUriPrefix($storage) . $destinationPath;
            }
        }

        return $model;
    }

    /**
     * Create managed file instance from source file
     *
     * @param string $sourceFile
     * @param string $storage,
     *            'public' or 'private, default 'public',
     * @param string $subfolder,
     *            subfolder
     * @return Managedfile
     */
    static public function createInstanceFromSourceFile($sourceFile, $storage = self::STORAGE_PUBLIC, $subfolder = '')
    {
        $model = null;

        if (is_readable($sourceFile)) {
            $basename = basename($sourceFile);
            $filename = uniqid('mf_') . $basename;
            $destinationPath = (empty($subfolder) ? '' : $subfolder . '/') . $filename;

            Storage::disk($storage)->put($destinationPath, file_get_contents($sourceFile));

            $model = new Managedfile();
            $model->file_name = $basename;
            $model->description = 'uploaded from ' . $sourceFile;
            $model->mime_type = mime_content_type($sourceFile);
            $model->file_size = Storage::disk($storage)->size($destinationPath);
            $model->file_uri = $model->generateUriPrefix($storage) . $destinationPath;
        }

        return $model;
    }
}
