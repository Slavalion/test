<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithColumnLimit;

class PickUpImport implements ToCollection, WithColumnLimit
{
    public function endColumn(): string
    {
        return 'F';
    }

    public function collection(Collection $collection)
    {
        //
    }
}
