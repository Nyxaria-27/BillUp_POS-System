<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Feature Toggles
    |--------------------------------------------------------------------------
    |
    | Toggle features on/off untuk aplikasi BillUp
    |
    */

    'discount' => env('FEATURE_DISCOUNT', true),
    'transaction_history' => env('FEATURE_TRANSACTION_HISTORY', true),
    'cashless_payment' => env('FEATURE_CASHLESS_PAYMENT', false), // Set false untuk fokus Cash sesuai modul
    'financial_reports' => env('FEATURE_FINANCIAL_REPORTS', true), // Set false untuk menyembunyikan menu Laporan Keuangan
];
