<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Auto-apply Transaction Middleware
    |--------------------------------------------------------------------------
    |
    | If set to true, the TransactionMiddleware will be automatically pushed
    | to the 'web' and 'api' middleware groups. If false, you must manually
    | attach the middleware (for example, using the 'transaction' alias).
    |
     */
    'auto_apply_global' => false,
    'auto_apply_web'    => false,
    'auto_apply_api'    => false,
];
