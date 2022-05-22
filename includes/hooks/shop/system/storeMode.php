<?php
/*
  $Id: storeMode.php
  $Loc: catalog/includes/hooks/shop/system/

  Store Mode 1.0.8.8
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

class hook_shop_system_storeMode {

  public $version = '1.0.8.8';

  public function listen_startApplication() {

    if ( defined('MODULE_STORE_MODE_STATUS') && MODULE_STORE_MODE_STATUS == 'True' ) {
      $this->load_lang();
      $allowed_ips_array = explode(',', MODULE_STORE_MODE_ALLOWED_IPS);
      if ( MODULE_STORE_MODE_MODE != 'online' && (!in_array(getenv('REMOTE_ADDR'), $allowed_ips_array) || strpos(MODULE_STORE_MODE_MODE, '-test') > 0) ) {
        $store_mode = str_replace('-test', '', MODULE_STORE_MODE_MODE);
        switch ($store_mode) {
          case 'custom' :
            $custom_pages_array = [];
            foreach (explode(';', MODULE_STORE_MODE_CUSTOM_PAGES) as $custompage) {
              $custompage = trim($custompage);
              if (!empty($custompage)) {
                $custom_pages_array[] = $custompage;
              }
            }
            $this->store_mode_redirect($custom_pages_array, 'index.php');
            break;
          case 'checkout' :
            $checkout_pages_array = [];
            foreach (explode(';', MODULE_STORE_MODE_CHECKOUT_PAGES) as $checkoutpage) {
              $checkoutpage = trim($checkoutpage);
              if (!empty($checkoutpage)) {
                $checkout_pages_array[] = $checkoutpage;
              }
            }

            // remove checkout module
            $GLOBALS['hooks']->set('getContentModules', 'disable_checkout', function ($parameters) {
              $parameters['modules'] = array_diff($parameters['modules'], ['cm_sc_checkout']);
            });

            $this->store_mode_redirect($checkout_pages_array, 'index.php');
            break;
          case 'account' :
            $account_pages_array = [];
            $account_pages = MODULE_STORE_MODE_LOGIN_ACCOUNT_PAGES . MODULE_STORE_MODE_CHECKOUT_PAGES;
            foreach (array_unique(explode(';', $account_pages)) as $accountpage) {
              $accountpage = trim($accountpage);
              if (!empty($accountpage)) {
                $account_pages_array[] = $accountpage;
              }
            }
            $this->store_mode_redirect($account_pages_array, 'shopping_cart.php');
            break;
        }

        // log customer out
        if ( isset($_SESSION['customer_id']) && MODULE_STORE_MODE_LOGOFF == 'True' ) {
          unset($_SESSION['customer_id']);
          $GLOBALS['hooks']->register_pipeline('reset');
          $GLOBALS['messageStack']->add_session('store_mode', MODULE_STORE_MODE_ACCOUNT_LOGGED_OFF . '<br><br>' . $this->get_offline_time(), 'alert');
        }

      }
    }

  }


// redirect function
  private function store_mode_redirect($pages_array, $default_page) {

    if (in_array(basename(Request::get_page()), $pages_array)) {
      if (in_array($default_page, $pages_array)) $default_page = 'index.php';
      if ( defined('NAVBAR_TITLE_2') && NAVBAR_TITLE_2 !='' ) {
        $page_name = NAVBAR_TITLE_2;
      } elseif ( defined('NAVBAR_TITLE_1') && NAVBAR_TITLE_1 !='' ) {
        $page_name = NAVBAR_TITLE_1;
      } elseif ( defined('NAVBAR_TITLE') && NAVBAR_TITLE !='' ) {
        $page_name = NAVBAR_TITLE;
      } elseif ( defined('HEADER_TITLE') && HEADER_TITLE !='' ) {
        $page_name = NAVBAR_TITLE;
      }

      $GLOBALS['messageStack']->add_session('store_mode', sprintf(MODULE_STORE_MODE_REDIRECT_MESSAGE, $page_name) . '<br><br>' . $this->get_offline_time(), 'alert');

      $back = count($_SESSION['navigation']->path)-2;
      while (isset($_SESSION['navigation']->path[$back]) && in_array($_SESSION['navigation']->path[$back]['page'], $pages_array) ) {
        $back--;
      }

      if (isset($_SESSION['navigation']->path[$back])) {
        Href::redirect(Guarantor::ensure_global('Linker')->build($_SESSION['navigation']->path[$back]['page'], array_diff($_SESSION['navigation']->path[$back]['get'], ['action'])));
      } else {
        Href::redirect(Guarantor::ensure_global('Linker')->build($default_page));
      }
    }
  }

// get offline time left
  private function get_offline_time() {
    if (MODULE_STORE_MODE_BACK_TIME != '') {
      $to_time = strtotime(date("Y-m-d H:i:s"));
      $from_time = strtotime(date('Y-m-d') . MODULE_STORE_MODE_BACK_TIME . ':00');
      if ($from_time < $to_time) $from_time += 24 * 60 * 60;
      $time_left = gmdate("H:i", round(abs($to_time - $from_time)));
      return  sprintf(MODULE_STORE_MODE_ACCOUNT_REOPEN_TIME, $time_left);
    }
  }

  function load_lang() {
    if (!defined('MODULE_STORE_MODE_REDIRECT_MESSAGE')) {
      require 'includes/languages/' . $_SESSION['language'] . '/hooks/shop/system/storeMode.php';
    }
  }

}
