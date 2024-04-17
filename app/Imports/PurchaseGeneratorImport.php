<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PurchaseGeneratorImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        // return $collection;
    }
}
