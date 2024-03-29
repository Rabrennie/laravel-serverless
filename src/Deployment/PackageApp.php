<?php

namespace LaravelServerless\Deployment;

use Exception;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class PackageApp extends DeploymentStep
{
    public function execute(): string
    {
        $storagePath = storage_path("laravel-serverless/");
        if (!file_exists($storagePath)) {
            mkdir($storagePath);
        }

        $zipName = strtolower(config('app.name')) . '.zip';
        $this->state->set('packagedAppZipName', $zipName);

        $zipLocation = $storagePath . $zipName;
        if (file_exists($zipLocation)) {
            unlink($zipLocation);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipLocation, ZipArchive::CREATE) !== true) {
            throw new Exception('Failed to create zip.');
        }

        $excludedPatterns = config('serverless.package.exclude');
        
        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(realpath(base_path())),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if ($file->isDir()) {
                continue;
            }

            $filePath = $file->getRealPath();
            $relativePath = str_replace(realpath(base_path()) . '/', '', $filePath);

            foreach ($excludedPatterns as $pattern) {
                if (fnmatch($pattern, $relativePath)) {
                    continue 2;
                }
            }

            $zip->addFile($filePath, $relativePath);
        }

        $zip->close();

        $this->state->set('packagedAppLocation', realpath($zipLocation));

        return 'Successfully packaged app.';
    }
}
