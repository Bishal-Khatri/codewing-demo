<?php

namespace App\Jobs;

use App\Exports\PeopleExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PeopleExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $path;

    /**
     * Create a new job instance.
     */
    public function __construct($path='')
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        (new PeopleExport($this->path))->queue('people'.time().'.xlsx');
    }
}
