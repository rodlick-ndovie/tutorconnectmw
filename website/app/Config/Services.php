<?php

namespace Config;

use CodeIgniter\Config\BaseService;

class Services extends BaseService
{
    // ...

    public static function paychangu($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('paychangu');
        }

        return new \App\Libraries\PayChangu();
    }

    // ...
}
