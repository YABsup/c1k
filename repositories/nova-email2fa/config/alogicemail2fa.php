<?php

return [
    /**
     * Disable or enable middleware.
     */
    'enabled' => env('EMAIL_2FA_ENABLED', true),

    'models' => [
        /**
         * Change this variable to path to user model.
         */
        'user'    => 'App\User',
    ],
    'tables' => [
        /**
         * Table in which users are stored.
         */
        'user' => 'users',
    ],
];
