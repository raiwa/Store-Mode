<?php
/*
* $Id: storeModeAdmin.php
* $Loc: /includes/hooks/admin/siteWide/
*
* Name: StoreMode
* Version: 1.6.3
* Release Date: 07/30/2024
* Author: Rainer Schmied
* 	 phoenixcartaddonsaddons.com / raiwa@phoenixcartaddons.com
*
* License: Released under the GNU General Public License
*
* Comments: Author: [Rainer Schmied @raiwa]
* Author URI: [www.phoenixcartaddons.com]
* 
* CE Phoenix, E-Commerce made Easy
* https://phoenixcart.org
* 
* Copyright (c) 2021 Phoenix Cart
* 
* 
*/

class hook_admin_siteWide_storeModeAdmin {

  public $version = '1.5.0';

  public function listen_injectBodyStart() {

    if ( defined('MODULE_STORE_MODE_STATUS') && MODULE_STORE_MODE_STATUS == 'True' && MODULE_STORE_MODE_MODE != 'online') {
      $GLOBALS['messageStack']->add(sprintf(constant('MODULE_STORE_MODE_MESSAGE_' . strtoupper($_SESSION['language'])), strtoupper(MODULE_STORE_MODE_MODE), MODULE_STORE_MODE_BACK_TIME)) . "\n";
    }

  }

}
