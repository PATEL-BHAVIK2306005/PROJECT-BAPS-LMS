<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FileController extends Controller
{
    /**
     * Serve a file stored in the database.
     *
     * @param string $path
     * @return \Illuminate\Http\Response
     */
    public function serve($path)
    {
        $connection = config('database.default');
        
        $file = DB::connection($connection)
            ->table('stored_files')
            ->where('path', $path)
            ->first();

        if (!$file) {
            abort(404, 'File not found');
        }

        return response($file->contents)
            ->header('Content-Type', $file->mime_type ?? 'application/octet-stream')
            ->header('Content-Length', $file->size ?? strlen($file->contents))
            ->header('Cache-Control', 'public, max-age=31536000')
            ->header('Content-Disposition', 'inline; filename="' . basename($path) . '"');
    }
}
