<?php

namespace Shuangz\Option;


trait RefreshesOptionCache
{
    public static function bootRefreshesOptionCache()
    {
        

        echo "bootRefreshesOptionCache";
    }

    /**
     *  Forget the cached permissions.
     */
    public function forgetCachedPermissions()
    {
        echo "RefreshesOptionCache";
    }
}
