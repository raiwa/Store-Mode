<?php
/*
  $Id: storeMode.php
  $Loc: catalog/includes/hooks/admin/siteWide/

  Store Mode 1.5.0
  by @raiwa
  info@oscaddons.com
  www.oscaddons.com
  
  updated for Phoenix Pro by @ecartz
  
  Copyright (c) 2021, Rainer Schmied
  All rights reserved.

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

class hook_admin_siteWide_storeModeAdmin {

  public $version = '1.5.0';

  public function listen_injectBodyStart() {

    if ( defined('MODULE_STORE_MODE_STATUS') && MODULE_STORE_MODE_STATUS == 'True' && MODULE_STORE_MODE_MODE != 'online') {
      $GLOBALS['messageStack']->add(sprintf(constant('MODULE_STORE_MODE_MESSAGE_' . strtoupper($_SESSION['language'])), strtoupper(MODULE_STORE_MODE_MODE), MODULE_STORE_MODE_BACK_TIME)) . "\n";
    }

  }

}
