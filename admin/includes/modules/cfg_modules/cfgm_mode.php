<?php
/*
* $Id: cfgm_mode.php
* $Loc: /admin/includes/modules/cfg_modules/
*
* Name: StoreMode
* Version: 1.6.2
* Release Date: 06/01/2024
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

   class cfgm_mode {

    const CODE = 'mode';
    const DIRECTORY = DIR_FS_CATALOG . 'includes/modules/mode/';
    const LANGUAGE_DIRECTORY = DIR_FS_CATALOG . 'includes/languages/';
    const KEY = 'MODULE_STORE_INSTALLED';
    const TITLE = MODULE_CFG_MODULE_STORE_MODE_TITLE;
    const TEMPLATE_INTEGRATION = true;

    const GET_HELP_LINK = 'https://www.phoenixcartaddons.com/contact_us.php?ceid=b1ors56vtso07u4l19v7l8rpcj';

    const GET_ADDONS_LINKS = [ADDONS_FREE => 'https://www.phoenixcartaddons.com/free-c-2.html',
                              ADDONS_COMMERCIAL => 'https://www.phoenixcartaddons.com/commercial-c-3.html'];

  }