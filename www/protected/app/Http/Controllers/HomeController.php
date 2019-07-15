<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {}

    /**
     * Show the application home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $apkFiles = $this->prepareApkFiles();
        return view('home', [
            'apkFiles' =>$apkFiles
        ]);
    }

    private function prepareApkFiles() {
        $path = storage_path('app/public/files/shares/*.apk');
        $apkFilePaths = glob($path);

        $apkFiles = [];
        foreach ($apkFilePaths as $path) {
            if (is_file($path)) {
                $basename = basename($path);
                $apkFiles[] = [
                    'basename' => $basename,
                    'size' => round(filesize($path) / 1024 / 1024, 1) . 'MB',
                    'url' => url("laravel-filemanager/files/shares/{$basename}")
                ];
            }
        }
        return $apkFiles;
    }
}
