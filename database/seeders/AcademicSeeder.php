<?php

namespace Database\Seeders;

use App\Enum\UserAbilityEnum;
use App\Enum\UserStatusEnum;
use App\Models\Academic;
use App\Models\CardApplicant;
use App\Models\CouponOwner;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicSeeder extends Seeder
{
    use WithoutModelEvents;

    public function __construct(protected int $count = 3000)
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
        $currentDay = Carbon::now()->subDay();
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
        $user_status = UserStatusEnum::ERASMUS;
        for ($i = $this->count; $i > 0; $i--) try {
            $academic = Academic::factory()->create([
                'status' => $user_status->value
            ]);
            if ($user_status->can(UserAbilityEnum::CARD_OWNERSHIP))
                CardApplicant::factory()->for($academic)->create();
            if ($user_status->can(UserAbilityEnum::COUPON_OWNERSHIP))
                CouponOwner::factory()->for($academic)->create();
        } catch (Exception $exception) {
        }
        $endTime = microtime(true);
        $elapsedTime = $endTime - $startTime;
        echo str_pad(number_format($elapsedTime * 1000, 2), 7, ' ', STR_PAD_LEFT) . 'ms';
        echo "\033[0;32m" . ' DONE' . "\033[0m" . PHP_EOL;
        $seeders = [
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
            $this->call($seeder, ['createdAtMoreThan' => $currentDay, 'count' => 100]);
            $endTime = microtime(true);
            $elapsedTime = $endTime - $startTime;
            echo str_pad(number_format($elapsedTime * 1000, 2), 7, ' ', STR_PAD_LEFT) . 'ms';
            echo "\033[0;32m" . ' DONE' . "\033[0m" . PHP_EOL;
        }
    }
}
