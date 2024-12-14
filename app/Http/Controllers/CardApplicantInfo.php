<?php

namespace App\Http\Controllers;

use App\Models\Academic;
use App\Models\CardApplicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CardApplicantInfo extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): ?CardApplicant
    {
        $hashCode = $request->validate([
            'hashCode' => ['required', 'string'],
        ])["hashCode"];
        $cachedData = Cache::get($hashCode)['email'];
        /** @var CardApplicant|null $cardApplicant */
        $cardApplicant = CardApplicant::whereEmail($cachedData)->withOnly(['addresses', 'departmentRelation:id,name'])->first();
        // Fetch applicant info for a specific user
        return $cardApplicant;
    }
}
