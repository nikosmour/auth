<?php

use App\Enum\UserAbilityEnum;
use App\Enum\UserStatusEnum;
use App\Http\Controllers\LoginController;
use App\Models\Academic;
use App\Models\CardApplicationStaff;
use App\Models\CouponStaff;
use App\Models\EntryStaff;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/login', LoginController::class)->name('login');
