<?php

namespace Database\Seeders;

use App\Enum\CardStatusEnum;
use App\Enum\UserAbilityEnum;
use App\Enum\UserStatusEnum;
use App\Models\Academic;
use App\Models\CardApplicant;
use App\Models\CardApplicationStaff;
use App\Models\CouponStaff;
use App\Models\EntryStaff;
use Carbon\Carbon;
use Database\Seeders\Classes\UserSeederPreparation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends UserSeederPreparation
{
    use WithoutModelEvents;

    public function __construct( $count = 50)
    {
        parent::__construct($count);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $totalCount=$this->count;
        $options=count($this->emailCounters);
        $optionsTotal=count(CardStatusEnum::enumByName());
        $this->count= intdiv($this->count , $optionsTotal)*$options;
        $AcademicCount=$totalCount-$this->count;
        $this->commonRun([
            [
              "class"=>DepartmentSeeder::class,
              "count"=>10,
            ],
            [
                "class"=>AcademicSeeder::class,
                "count"=>$AcademicCount,
            ],

        ]);
    }
    protected function whenInit($status): bool{
        return !$this->isAcademic($status);
    }


    protected function initCounters($status):void
    {
        $key=$status->name;
        $lowercaseKey=strtolower($key);
        if ($status->can(UserAbilityEnum::COUPON_SELL)) {
        $this->emailCounters[$key] = CouponStaff::where('status', $status->value)
            ->where('email', 'like', $lowercaseKey . '%')
            ->orderByDesc('id')
            ->value('id') ??  0;
        } elseif ($status->can(UserAbilityEnum::CARD_APPLICATION_CHECK)) {
        $this->emailCounters[$key] = CardApplicationStaff::where('status', $status->value)
            ->where('email', 'like', $lowercaseKey . '%')
            ->orderByDesc('id')
            ->value('id') ?? 0;
        } elseif ($status->can(UserAbilityEnum::ENTRY_CHECK)) {
        $this->emailCounters[$key] = EntryStaff::where('status', $status->value)
            ->where('email', 'like', $lowercaseKey . '%')
            ->orderByDesc('id')
            ->value('id') ?? 0;
        }
    }

    public function createUser(int $count, UserStatusEnum|null $users_status = null): void
    {
        $keys=array_keys($this->emailCounters);
        $numberOfStatus=count($keys);
        for ($i = $count; $i > 0; $i--) {
            $user_status=$users_status ?? UserStatusEnum::enumByName()[$keys[$i % $numberOfStatus]];
            $status=$user_status->name;
            $this->emailCounters[$status]++;
            $email = $this->generateEmail($status);

            if ($user_status->can(UserAbilityEnum::COUPON_SELL)) {
                CouponStaff::factory()->create([
                    'status' => $user_status->value,
                    'email' => $email
                ]);
            } elseif ($user_status->can(UserAbilityEnum::CARD_APPLICATION_CHECK)) {
                CardApplicationStaff::factory()->create([
                    'status' => $user_status->value,
                    'email' => $email
                ]);
            } elseif ($user_status->can(UserAbilityEnum::ENTRY_CHECK)) {
                EntryStaff::factory()->create([
                    'status' => $user_status->value,
                    'email' => $email
                ]);
            }
        }
    }
}
