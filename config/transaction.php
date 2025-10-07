<?php

return [
    'max_amount' => env('TXN_MAX_AMOUNT', 50000),
    'throttle_max' => env('TXN_THROTTLE_MAX', 10),
    'throttle_mins' => env('TXN_THROTTLE_MINS', 60)
];