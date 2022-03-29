<?php

return [
    'jobs'      =>  [

    ],

    /*
    |--------------------------------------------------------------------------
    | System User Identity
    |--------------------------------------------------------------------------
    |
    | This is the user which will be displayed whenever panned action is
    | performed, for example, a console command or CRON job.
    |
    | These are credentials which will be used for user creation.
    |
    */
    'system_user'           =>  [
        'identifier'        =>  env('TASK_RUNNER_SYSTEM_USER_ID', 1),
        'email'             =>  env('TASK_RUNNER_SYSTEM_USER_EMAIL', 'admin@leads.su'),
        'password'          =>  env('TASK_RUNNER_SYSTEM_USER_PASSWORD', '1234567890'),
    ],
];
