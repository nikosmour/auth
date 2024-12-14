<?php

namespace Database\Seeders;

use App\Enum\UserAbilityEnum;
use App\Enum\UserStatusEnum;
use App\Models\Academic;
use App\Models\CardApplicant;
use App\Models\CardApplicationStaff;
use App\Models\CouponStaff;
use App\Models\EntryStaff;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function __construct(protected int $count = 1000)
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

        // Initialize email counters dynamically from the database
        $emailCounters = collect(UserStatusEnum::cases())
            ->map(fn($status) => strtolower($status->name))
            ->sort()
            ->values()
            ->mapWithKeys(fn($name, $index) => [$name => ($index + 1)*1000000])
            ->toArray();
        foreach (UserStatusEnum::cases() as $status) {
            $lowercaseKey = strtolower($status->name);
            if ($status->canAny([
                UserAbilityEnum::CARD_OWNERSHIP,
                UserAbilityEnum::COUPON_OWNERSHIP
            ])) {
                $emailCounters[$lowercaseKey] = Academic::where('status', $status->value)
                    ->where('email', 'like', $lowercaseKey . '%')
                    ->orderByDesc('a_m')
                    ->value('a_m') ?? $emailCounters[$lowercaseKey]  ;
            } elseif ($status->can(UserAbilityEnum::COUPON_SELL)) {
                $emailCounters[$lowercaseKey] = CouponStaff::where('status', $status->value)
                    ->where('email', 'like', $lowercaseKey . '%')
                    ->orderByDesc('id')
                    ->value('id') ??  0;
            } elseif ($status->can(UserAbilityEnum::CARD_APPLICATION_CHECK)) {
                $emailCounters[$lowercaseKey] = CardApplicationStaff::where('status', $status->value)
                    ->where('email', 'like', $lowercaseKey . '%')
                    ->orderByDesc('id')
                    ->value('id') ?? 0;
            } elseif ($status->can(UserAbilityEnum::ENTRY_CHECK)) {
                $emailCounters[$lowercaseKey] = EntryStaff::where('status', $status->value)
                    ->where('email', 'like', $lowercaseKey . '%')
                    ->orderByDesc('id')
                    ->value('id') ?? 0;
           }
        }


        // Helper to generate unique email
        $generateEmail = function ($lowercaseKey) use (&$emailCounters) {
            $email = "{$lowercaseKey}{$emailCounters[$lowercaseKey]}@example.com";
            return $email;
        };

        for ($i = $this->count; $i > 0; $i--) {
            $user_status = collect(UserStatusEnum::cases())->random();
            $lowercaseKey= strtolower($user_status->name);
            $emailCounters[$lowercaseKey]++;
            $email = $generateEmail($lowercaseKey);

            if ($user_status->canAny([
                UserAbilityEnum::CARD_OWNERSHIP,
                UserAbilityEnum::COUPON_OWNERSHIP
            ])) {
                $academic = Academic::factory()->create([
                    'status' => $user_status->value,
                    'email' => $email,
                    'a_m'=>$emailCounters[$lowercaseKey],
                    'academic_id'=>$emailCounters[$lowercaseKey]+2*10**15 ,
                ]);

                if ($user_status->can(UserAbilityEnum::CARD_OWNERSHIP)) {
                    CardApplicant::factory()->for($academic)->create();
                }
            } elseif ($user_status->can(UserAbilityEnum::COUPON_SELL)) {
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

        $endTime = microtime(true);
        $elapsedTime = $endTime - $startTime;
        echo str_pad(number_format($elapsedTime * 1000, 2), 7, ' ', STR_PAD_LEFT) . 'ms';
        echo "\033[0;32m" . ' DONE' . "\033[0m" . PHP_EOL;

        $seeders = [
            DepartmentSeeder::class,
            CardApplicantSeeder::class,
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
