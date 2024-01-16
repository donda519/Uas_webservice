<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FilesLinkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create symlink link for files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // check if exists public/storage
        if ( file_exists(app()->basePath('public/storage/')) ) {
            $this->error("Symlink exists!");
            exit(0);
        }
        // check if exists storage/app/public
        if ( !file_exists(storage_path('app/public')) ) {
            $this->error("Storage folder [app/public] not exists!");
            exit(0);
        }
        // actually, we can create folders if not exists
        // using mkdir() php function, or File::makeDirectory()
        // but the second one still uses mkdir() behind the scene
    
        app()->make('files')
            ->link(storage_path('app/public'), rtrim(app()->basePath('public/storage/'), '/'));
        $this->info("Symlink created!");
    }
}
