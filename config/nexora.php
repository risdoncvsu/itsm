<?php

return [
    // Temporary QA switch: permits authenticated root admins to open module
    // routes without impersonating a client employee. Keep false in normal use.
    'root_admin_module_testing' => env('ROOT_ADMIN_MODULE_TESTING', false),
];
