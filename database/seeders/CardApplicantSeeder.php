<?php

namespace Database\Seeders;

use App\Models\Address as Address;
use App\Models\CardApplicant;
use App\Models\CardApplication;
use App\Models\CardApplicationDocument;
use App\Models\Department;
use App\Models\HasCardApplicantComment;
use Database\Seeders\Classes\CreatedAtMoreThanSeeder;

class CardApplicantSeeder extends CreatedAtMoreThanSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cardApplicants = CardApplicant::whereDoesntHave('cardApplications')->where('created_at', '>', $this->createdAtMoreThan)->cursor();
        $departments = Department::all();
        foreach ($cardApplicants as $cardApplicant) {
            Address::factory()->permanent()->for($cardApplicant)->create();
            Address::factory()->notPermanent()->for($cardApplicant)->create();
            $cardApplicant->department_id = $departments->random()->id;
            $cardApplicant->save();
            CardApplication::factory()->for($cardApplicant)->has(CardApplicationDocument::factory()->count(3))->has(HasCardApplicantComment::factory()->randomStatus()->count(1), 'applicantComments')->create();

        }
    }
}
