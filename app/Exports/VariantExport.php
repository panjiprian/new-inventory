<?php

namespace App\Exports;

use App\Models\Variant;
use Maatwebsite\Excel\Concerns\FromCollection;

class VariantExport implements FromCollection
{
    public function collection()
    {
        return Variant::all();
    }
}
