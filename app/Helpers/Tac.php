<?php

namespace App\Helpers;

use App\Models\TacNumber;

class Tac
{
    public function generateNewTac(string $mobile, string $purpose = TacNumber::PURPOSE_RESET_PASSWORD)
    {
        $mobile     = (new Misc())->phoneStoreFormat($mobile);

        $user_tac   = TacNumber::where('mobile_no', $mobile)->where('purpose', $purpose)->active()->notExpired()->unverified();

        // deactivate previous active tac
        if ($old_tac = (clone $user_tac)->first()) {
            $old_tac->update([
                'status' => TacNumber::STATUS_INACTIVE
            ]);
        }

        if (config('app.env') === 'production') {
            $raw_tac    = rand(100000, 999999);

            while ((clone $user_tac)->where('tac', $raw_tac)->exists()) {
                $raw_tac = rand(100000, 999999);
            }
        } else {
            $raw_tac = 111111;
        }

        $new_tac = TacNumber::create([
            'purpose' => $purpose,
            'mobile_no' => $mobile,
            'tac' => $raw_tac,
            'status' => TacNumber::STATUS_ACTIVE,
            'expired_at' => now()->addMinutes(TacNumber::ACTIVE_PERIOD_IN_MINUTES),
        ]);

        return [
            'purpose'       => $new_tac->purpose, // purpose must be the same as the key in "mail" language file
            'raw_tac'       => $raw_tac,
            'expired_at'    => $new_tac->expired_at->toDateTimeString()
        ];
    }
}
