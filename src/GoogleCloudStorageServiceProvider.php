<?php

namespace Superbalist\LaravelGoogleCloudStorage;

use Google\Cloud\Storage\StorageClient;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

class GoogleCloudStorageServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $factory = $this->app->make('filesystem'); /** @var FilesystemManager $factory */
        $factory->extend('gcs', function ($app, $config) {
            $storageClient = new StorageClient([
                'projectId' => $config['project_id'],
                'keyFilePath' => array_get($config, 'key_file'),
            ]);
            $bucket = $storageClient->bucket($config['bucket']);
            $pathPrefix = array_get($config, 'path_prefix');

            $adapter = new GoogleStorageAdapter($storageClient, $bucket, $pathPrefix);

            return new Filesystem($adapter);
        });

    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        //
    }
}