<?php

namespace App\Filesystem;

use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FileAttributes;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\Config;
use League\Flysystem\StorageAttributes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseAdapter implements FilesystemAdapter
{
    protected string $connection;

    public function __construct()
    {
        // Use default connection configured at runtime (mysql_online or fallback mysql)
        $this->connection = config('database.default');
    }

    protected function db()
    {
        return DB::connection($this->connection)->table('stored_files');
    }

    public function fileExists(string $path): bool
    {
        try {
            return $this->db()->where('path', $path)->exists();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function directoryExists(string $path): bool
    {
        try {
            return $this->db()->where('path', 'like', $path . '/%')->exists();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function write(string $path, string $contents, Config $config): void
    {
        try {
            $size = strlen($contents);
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($contents) ?: 'application/octet-stream';

            DB::connection($this->connection)->transaction(function () use ($path, $contents, $size, $mimeType) {
                $exists = $this->db()->where('path', $path)->exists();
                if ($exists) {
                    $this->db()->where('path', $path)->update([
                        'contents' => $contents,
                        'mime_type' => $mimeType,
                        'size' => $size,
                        'updated_at' => now(),
                    ]);
                } else {
                    $this->db()->insert([
                        'path' => $path,
                        'contents' => $contents,
                        'mime_type' => $mimeType,
                        'size' => $size,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
        } catch (\Exception $e) {
            throw new \League\Flysystem\UnableToWriteFile($e->getMessage(), 0, $e);
        }
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        $data = stream_get_contents($contents);
        $this->write($path, $data, $config);
    }

    public function read(string $path): string
    {
        try {
            $row = $this->db()->where('path', $path)->first();
            if (!$row) {
                throw new \League\Flysystem\UnableToReadFile("File not found: {$path}");
            }
            return (string) $row->contents;
        } catch (\Exception $e) {
            throw new \League\Flysystem\UnableToReadFile($e->getMessage(), 0, $e);
        }
    }

    public function readStream(string $path)
    {
        $contents = $this->read($path);
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $contents);
        rewind($stream);
        return $stream;
    }

    public function delete(string $path): void
    {
        try {
            $this->db()->where('path', $path)->delete();
        } catch (\Exception $e) {
            throw new \League\Flysystem\UnableToDeleteFile($e->getMessage(), 0, $e);
        }
    }

    public function deleteDirectory(string $path): void
    {
        try {
            $this->db()->where('path', 'like', $path . '/%')->delete();
        } catch (\Exception $e) {
            throw new \League\Flysystem\UnableToDeleteDirectory($e->getMessage(), 0, $e);
        }
    }

    public function createDirectory(string $path, Config $config): void
    {
        // Virtual directories do not require insertion
    }

    public function setVisibility(string $path, string $visibility): void
    {
        // Visibilities are public by default
    }

    public function visibility(string $path): FileAttributes
    {
        return new FileAttributes($path, null, 'public');
    }

    public function mimeType(string $path): FileAttributes
    {
        try {
            $row = $this->db()->where('path', $path)->first(['mime_type']);
            return new FileAttributes($path, null, null, null, $row ? $row->mime_type : 'application/octet-stream');
        } catch (\Exception $e) {
            throw new \League\Flysystem\UnableToRetrieveMetadata($e->getMessage(), 0, $e);
        }
    }

    public function lastModified(string $path): FileAttributes
    {
        try {
            $row = $this->db()->where('path', $path)->first(['updated_at']);
            $timestamp = $row ? strtotime($row->updated_at) : time();
            return new FileAttributes($path, null, null, $timestamp);
        } catch (\Exception $e) {
            throw new \League\Flysystem\UnableToRetrieveMetadata($e->getMessage(), 0, $e);
        }
    }

    public function fileSize(string $path): FileAttributes
    {
        try {
            $row = $this->db()->where('path', $path)->first(['size']);
            return new FileAttributes($path, $row ? (int) $row->size : 0);
        } catch (\Exception $e) {
            throw new \League\Flysystem\UnableToRetrieveMetadata($e->getMessage(), 0, $e);
        }
    }

    public function listContents(string $path, bool $deep): iterable
    {
        try {
            $query = $this->db();
            if ($path !== '') {
                $query->where('path', 'like', $path . '/%');
            }
            $rows = $query->get(['path', 'size', 'updated_at', 'mime_type']);
            
            $results = [];
            foreach ($rows as $row) {
                $results[] = new FileAttributes(
                    $row->path,
                    (int) $row->size,
                    'public',
                    strtotime($row->updated_at),
                    $row->mime_type
                );
            }
            return $results;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function move(string $source, string $destination, Config $config): void
    {
        try {
            $this->db()->where('path', $source)->update([
                'path' => $destination,
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            throw new \League\Flysystem\UnableToMoveFile($e->getMessage(), 0, $e);
        }
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        try {
            $row = $this->db()->where('path', $source)->first();
            if ($row) {
                $this->db()->insert([
                    'path' => $destination,
                    'contents' => $row->contents,
                    'mime_type' => $row->mime_type,
                    'size' => $row->size,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            throw new \League\Flysystem\UnableToCopyFile($e->getMessage(), 0, $e);
        }
    }
}
