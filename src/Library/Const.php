<?php

//Product kind
define('BC_PRODUCT_SINGLE', 0);
define('BC_PRODUCT_BUILD', 1);
define('BC_PRODUCT_GROUP', 2);
//Product property
define('BC_PROPERTY_PHYSICAL', 'physical');
define('BC_PROPERTY_DOWNLOAD', 'download');
// list ID admin guard
define('BC_GUARD_ADMIN', ['1']); // admin
// list ID language guard
define('BC_GUARD_LANGUAGE', ['1', '2']); // vi, en
// list ID currency guard
define('BC_GUARD_CURRENCY', ['1', '2']); // vndong , usd
// list ID ROLES guard
define('BC_GUARD_ROLES', ['1', '2']); // admin, only view

/**
 * Admin define
 */
define('BC_ADMIN_MIDDLEWARE', ['web', 'admin']);
define('BC_FRONT_MIDDLEWARE', ['web', 'front']);
define('BC_API_MIDDLEWARE', ['api', 'api.extent']);
define('BC_CONNECTION', 'mysql');
define('BC_CONNECTION_LOG', 'mysql');
//Prefix url admin
define('BC_ADMIN_PREFIX', config('const.ADMIN_PREFIX'));
//Prefix database
define('BC_DB_PREFIX', config('const.DB_PREFIX'));
// Root ID store
define('BC_ID_ROOT', 1);
