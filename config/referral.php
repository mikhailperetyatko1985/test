<?php

return [

    'percent' => (int) env('REFERRAL_PERCENT', 10),

    // Размер страницы для GET /api/referrals/my.
    'per_page' => max(1, (int) env('REFERRAL_PER_PAGE', 50)),
];
