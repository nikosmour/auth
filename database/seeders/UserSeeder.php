<?php

namespace Database\Seeders;

use App\Enum\UserAbilityEnum;
use App\Enum\UserStatusEnum;
use App\Models\Academic;
use App\Models\CardApplicant;
use App\Models\CardApplicationStaff;
use App\Models\CouponOwner;
use App\Models\CouponStaff;
use App\Models\EntryStaff;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function __construct(protected int $count = 50)
    {
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        echo str_pad('creating new users', 120, '.', STR_PAD_RIGHT);
        $startTime = microtime(true);
        $currentDay = Carbon::now();
        if (0 == Academic::whereEmail('erasmus@example.com')->count())
            Academic::factory()
                ->has(CardApplicant::factory()->count(1))
                ->has(CouponOwner::factory()->count(1))
                ->create([
                'status' => UserStatusEnum::ERASMUS,
                'email' => 'erasmus@example.com'
            ]);
        if (0 == Academic::whereEmail('researcher@example.com')->count())
            Academic::factory()
                ->has(CouponOwner::factory()->count(1))
                ->create([
                'status' => UserStatusEnum::RESEARCHER,
                'email' => 'researcher@example.com'
            ]);

        if (0 == CouponStaff::whereEmail('staff_coupon@example.com')->count())
            CouponStaff::factory()->create([
                'status' => UserStatusEnum::STAFF_COUPON,
                'email' => 'staff_coupon@example.com'
            ]);
        if (0 == CardApplicationStaff::whereEmail('staff_card@example.com')->count())
            CardApplicationStaff::factory()->create([
                'status' => UserStatusEnum::STAFF_CARD,
                'email' => 'staff_card@example.com'
            ]);
        if (0 == EntryStaff::whereEmail('staff_entry@example.com')->count())
            EntryStaff::factory()->create([
                'status' => UserStatusEnum::STAFF_ENTRY,
                'email' => 'staff_entry@example.com'
            ]);
        for ($i = $this->count; $i > 0; $i--) {
            $user_status = collect(UserStatusEnum::cases())->random();

            if ($user_status->canAny([
                UserAbilityEnum::CARD_OWNERSHIP,
                UserAbilityEnum::COUPON_OWNERSHIP])) {
                $academic = Academic::factory()->create([
                    'status' => $user_status->value
                ]);
                if ($user_status->can(UserAbilityEnum::CARD_OWNERSHIP))
                    CardApplicant::factory()->for($academic)->create();
                if ($user_status->can(UserAbilityEnum::COUPON_OWNERSHIP))
                    CouponOwner::factory()->for($academic)->create();
            } elseif ($user_status->can(UserAbilityEnum::COUPON_SELL))
                CouponStaff::factory()->create([
                    'status' => $user_status->value
                ]);
            elseif ($user_status->can(UserAbilityEnum::CARD_APPLICATION_CHECK))
                CardApplicationStaff::factory()->create([
                    'status' => $user_status->value
                ]);
            elseif ($user_status->can(UserAbilityEnum::ENTRY_CHECK))
                EntryStaff::factory()->create([
                    'status' => $user_status->value
                ]);
        }
        $endTime = microtime(true);
        $elapsedTime = $endTime - $startTime;
        echo str_pad(number_format($elapsedTime * 1000, 2), 7, ' ', STR_PAD_LEFT) . 'ms';
        echo "\033[0;32m" . ' DONE' . "\033[0m" . PHP_EOL;
        $seeders = [
            DepartmentSeeder::class,
            PurchaseCouponSeeder::class,
            TransferCouponSeeder::class,
            UsageCardSeeder::class,
            UsageCouponSeeder::class,
            CardApplicantSeeder::class,
            CardApplicationCheckingSeeder::class
        ];
        foreach ($seeders as $seeder) {
            echo str_pad(class_basename($seeder), 120, '.', STR_PAD_RIGHT);
            $startTime = $endTime;
            $this->call($seeder, ['createdAtMoreThan' => $currentDay]);
            $endTime = microtime(true);
            $elapsedTime = $endTime - $startTime;
            echo str_pad(number_format($elapsedTime * 1000, 2), 7, ' ', STR_PAD_LEFT) . 'ms';
            echo "\033[0;32m" . ' DONE' . "\033[0m" . PHP_EOL;
        }
    }
}
