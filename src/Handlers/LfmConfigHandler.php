<?php

namespace BlackCart\Core\Handlers;

class LfmConfigHandler extends \UniSharp\LaravelFilemanager\Handlers\ConfigHandler
{
    public function userField()
    {
        // If domain is root, dont split folder
        if (session('adminStoreId') == BC_ID_ROOT) {
            return ;
        }

        if (bc_config_global('MultiVendorPro')) {
            return session('adminStoreId');
        } else {
            return;
        }
    }
}
