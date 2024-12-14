<?php

namespace Database\Seeders\Classes;

use App\Models\Address as Address;
use App\Models\CardApplication;
use App\Models\CardApplicationDocument;
use App\Models\HasCardApplicantComment;
use Illuminate\Database\Seeder;

abstract class CreatedAtMoreThanSeeder extends Seeder
{

    /**
     * @param string $createdAtMoreThan
     */
    public function __construct(protected string $createdAtMoreThan = '1900-01-01 12:00:00')
    {
    }
}
