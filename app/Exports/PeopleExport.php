<?php

namespace App\Exports;

use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;

class PeopleExport implements FromArray, ShouldQueue, WithHeadings
{
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    use Exportable;
    public $data;
    public function __construct($data=[])
    {
        $this->data = $data;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        $characters = file_get_contents(Storage::disk('public')->path($this->data));
        $data = json_decode($characters, true);
        return $data;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone',
            'Address'
        ];
    }
}
