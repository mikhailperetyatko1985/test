<?php

namespace Database\Seeders;

use App\Enums\PaymentType;
use App\Enums\ReferralStatus;
use App\Models\Master;
use App\Models\Payment;
use App\Models\Referral;
use Illuminate\Database\Seeder;

/**
 * Демо-данные.
 *
 * Маша — реферер, у неё код MASHA10. По нему пришли четыре мастера
 * с разной историей платежей. Лена пришла сама, без кода.
 *
 * Платежи создаются через модель, поэтому обработчик платежей
 * отрабатывает так же, как в бою.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $masha = Master::create([Master::F_NAME => 'Маша', Master::F_REFERRAL_CODE => 'MASHA10']);
        $lena = Master::create([Master::F_NAME => 'Лена', Master::F_REFERRAL_CODE => 'LENA77']);

        $ira = Master::create([Master::F_NAME => 'Ира', Master::F_REFERRAL_CODE => 'IRA31']);
        $olya = Master::create([Master::F_NAME => 'Оля', Master::F_REFERRAL_CODE => 'OLYA22']);
        $katya = Master::create([Master::F_NAME => 'Катя', Master::F_REFERRAL_CODE => 'KATYA05']);
        $dasha = Master::create([Master::F_NAME => 'Даша', Master::F_REFERRAL_CODE => 'DASHA64']);

        foreach ([$ira, $olya, $katya, $dasha] as $referred) {
            Referral::create([
                Referral::F_REFERRER_MASTER_ID => $masha->{Master::F_ID},
                Referral::F_REFERRED_MASTER_ID => $referred->{Master::F_ID},
                Referral::F_STATUS => ReferralStatus::Pending,
            ]);
        }

        // Ира: оплатила картой, потом продлила.
        Payment::create([Payment::F_MASTER_ID => $ira->{Master::F_ID}, Payment::F_AMOUNT => 3000, Payment::F_TYPE => PaymentType::Card]);
        Payment::create([Payment::F_MASTER_ID => $ira->{Master::F_ID}, Payment::F_AMOUNT => 3000, Payment::F_TYPE => PaymentType::Card]);

        // Оля: сидит на промокоде, денег не платила.
        Payment::create([Payment::F_MASTER_ID => $olya->{Master::F_ID}, Payment::F_AMOUNT => 0, Payment::F_TYPE => PaymentType::Promo]);

        // Катя: пробный период, платежей нет.
        Payment::create([Payment::F_MASTER_ID => $katya->{Master::F_ID}, Payment::F_AMOUNT => 0, Payment::F_TYPE => PaymentType::Trial]);

        // Даша: неудачное списание на 0, следом настоящая оплата.
        Payment::create([Payment::F_MASTER_ID => $dasha->{Master::F_ID}, Payment::F_AMOUNT => 0, Payment::F_TYPE => PaymentType::Card]);
        Payment::create([Payment::F_MASTER_ID => $dasha->{Master::F_ID}, Payment::F_AMOUNT => 2000, Payment::F_TYPE => PaymentType::Card]);

        // Лена пришла без реферального кода.
        Payment::create([Payment::F_MASTER_ID => $lena->{Master::F_ID}, Payment::F_AMOUNT => 3000, Payment::F_TYPE => PaymentType::Sbp]);
    }
}
