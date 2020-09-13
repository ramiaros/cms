<?php
class Config {
    static public $domain = 'default';
    static public $appVersion = '0.0.2';
    static public $apiUrl = '/wp-content/themes/cms/api.php';
    static public $registerPage = '/?page=user.register';
    static public $resignResultPage = '/?page=user.resign_result';
    static public $adminUserList = '/?page=admin.user.list';

    /// If it is set to true, only verified mobile can be registered.
    static public $verifiedMobileOnly = true;
    /// If it is set to true, the mobile number becomes unique in Database.
    static public $uniqueMobile = true;
}


/**
 * Get host name
 */
if (isset($_SERVER['HTTP_HOST'])) {
    $_host = $_SERVER['HTTP_HOST'];
} else {
    $_host = null;
}


/**
 * Match host name to page.
 */
if ( isset($_REQUEST['page']) && strpos($_REQUEST['page'], 'admin.') !== false ) { // if 'page' HTTP variable has 'admin', then it uses 'admin' theme.
    Config::$domain = 'admin';
}
else if ($_host == 'wp-blog.philgo.com' ) {
    Config::$domain = 'blog';
} else if ($_host == 'wp-realestate.philgo.com' ) {
    Config::$domain = 'realestate';
}
