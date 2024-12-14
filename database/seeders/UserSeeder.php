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

        // Initialize email counters dynamically from the database
        $emailCounters = array_fill_keys(array_map(fn($status) => strtolower($status->name), UserStatusEnum::cases()), 1);

        foreach (UserStatusEnum::cases() as $status) {
            $lowercaseKey = strtolower($status->name);
            $latestEmail= null;
            if ($status->canAny([
                UserAbilityEnum::CARD_OWNERSHIP,
                UserAbilityEnum::COUPON_OWNERSHIP
            ])) {
                $latestEmail = Academic::where('status', $status->value)
                    ->where('email', 'like', $lowercaseKey . '%')
                    ->orderByDesc('email')
                    ->value('email');
            } elseif ($status->can(UserAbilityEnum::COUPON_SELL)) {
                $latestEmail = CouponStaff::where('status', $status->value)
                    ->where('email', 'like', $lowercaseKey . '%')
                    ->orderByDesc('email')
                    ->value('email');
            } elseif ($status->can(UserAbilityEnum::CARD_APPLICATION_CHECK)) {
                $latestEmail = CardApplicationStaff::where('status', $status->value)
                    ->where('email', 'like', $lowercaseKey . '%')
                    ->orderByDesc('email')
                    ->value('email');
            } elseif ($status->can(UserAbilityEnum::ENTRY_CHECK)) {
                $latestEmail = EntryStaff::where('status', $status->value)
                    ->where('email', 'like', $lowercaseKey . '%')
                    ->orderByDesc('email')
                    ->value('email');
           }
            if ($latestEmail && preg_match('/\d+/', $latestEmail, $matches)) {
                $emailCounters[$lowercaseKey] = (int)$matches[0] + 1;
            }
        }

        // Helper to generate unique email
        $generateEmail = function ($statusKey) use (&$emailCounters) {
            $lowercaseKey = strtolower($statusKey);
            $email = "{$lowercaseKey}{$emailCounters[$lowercaseKey]}@example.com";
            $emailCounters[$lowercaseKey]++;
            return $email;
        };

        for ($i = $this->count; $i > 0; $i--) {
            $user_status = collect(UserStatusEnum::cases())->random();
            $email = $generateEmail($user_status->name);

            if ($user_status->canAny([
                UserAbilityEnum::CARD_OWNERSHIP,
                UserAbilityEnum::COUPON_OWNERSHIP
            ])) {
                $academic = Academic::factory()->create([
                    'status' => $user_status->value,
                    'email' => $email
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
