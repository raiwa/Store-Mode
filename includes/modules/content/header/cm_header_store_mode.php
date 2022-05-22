<?php
/*
  $Id: cm_header_store_mode.php
  $Loc: catalog/includes/modules/content/header/

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

  class cm_header_store_mode extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_HEADER_STORE_MODE_';

    public function __construct() {
      parent::__construct(__FILE__);

      if (!defined('MODULE_STORE_MODE_STATUS') || MODULE_STORE_MODE_STATUS != 'True' ) {
        $this->description = '<div class="secWarning">' .
                                 MODULE_CONTENT_HEADER_STORE_MODE_STORE_MODULE_WARNING .
                             '  <a href="modules.php?set=mode&list=new">' . MODULE_CONTENT_HEADER_STORE_MODE_STORE_MODULE_INSTALL_NOW . '</a>
                             </div>' .
                             $this->description;
      }

      if ( !defined('MODULE_STORE_MODE_STATUS') || MODULE_STORE_MODE_STATUS != 'True' ) {
        $this->enabled = false;
      }
    }

    function execute() {
      global $messageStack;

      $output = null;

      if (defined('MODULE_STORE_MODE_STATUS') && MODULE_STORE_MODE_STATUS == 'True' ) {

        // check if to show the checkout message
        if ( strpos(MODULE_STORE_MODE_MODE, 'checkout') > -1 && !Text::is_empty(MODULE_CONTENT_HEADER_STORE_MODE_CHECKOUT_PAGES)) {
          $checkout_pages_array = [];

          foreach (explode(';', MODULE_CONTENT_HEADER_STORE_MODE_CHECKOUT_PAGES) as $page) {
            $page = trim($page);

            if (!empty($page)) {
              $checkout_pages_array[] = $page;
            }
          }

        // create the checkout message
          if (in_array(basename(Request::get_page()), $checkout_pages_array)) {
            $output .= 	'<div class="alert alert-warning">';
            $output .= 	'  <div class="card-body">' .  MODULE_CONTENT_HEADER_STORE_MODE_CHECKOUT_MESSAGE . '<br><br>' . $this->get_offline_time() . '</div>';
            if ($messageStack->size('store_mode') > 0) {
              $messageStack->add('store_mode_short', MODULE_CONTENT_HEADER_STORE_MODE_CUSTOM_MESSAGE_SHORT, 'alert');
              $output .= $messageStack->output('store_mode_short');
            }
            $output .= 	'</div>';
          }
        }

        // check if to show the account message
        if ( strpos(MODULE_STORE_MODE_MODE, 'account') > -1 && !Text::is_empty(MODULE_CONTENT_HEADER_STORE_MODE_ACCOUNT_PAGES)) {
          $account_pages_array = [];

          foreach (explode(';', MODULE_CONTENT_HEADER_STORE_MODE_ACCOUNT_PAGES) as $page) {
            $page = trim($page);

            if (!empty($page)) {
              $account_pages_array[] = $page;
            }
          }

        // create the account message
          if (in_array(basename(Request::get_page()), $account_pages_array)) {
            $output .= 	'<div class="alert alert-warning">';
            $output .= 	'  <div class="card-body">' .  MODULE_CONTENT_HEADER_STORE_MODE_ACCOUNT_MESSAGE . '<br><br>' . $this->get_offline_time() . '</div>';
            if ($messageStack->size('store_mode') > 0) {
              $messageStack->add('store_mode_short', MODULE_CONTENT_HEADER_STORE_MODE_CUSTOM_MESSAGE_SHORT, 'alert');
              $output .= $messageStack->output('store_mode_short');
            }
            $output .= 	'</div>';
          }

        }

        // create the administrators message
        $allowed_ips_array = [];
        $allowed_ips_array = explode(',', MODULE_STORE_MODE_ALLOWED_IPS);
        if ( MODULE_STORE_MODE_MODE != 'online' && strpos(MODULE_STORE_MODE_MODE, '-test') == 0 && in_array(getenv('REMOTE_ADDR'), $allowed_ips_array) ) {
          $output = 	'<div class="jumbotron alert-danger">';
          $output .= 	'  <p>' .  sprintf(constant('MODULE_STORE_MODE_MESSAGE_' . strtoupper($_SESSION['language'])), strtoupper(MODULE_STORE_MODE_MODE), $this->get_offline_time() ) . '</p>';
          $output .= 	'</div>';
        }

        // check if to show the custom area/redirect message
        if ( $messageStack->size('store_mode') > 0 && $output == '' ) {
          $output = $messageStack->output('store_mode');
        }

        if ( $output != '') { // show message
          $content_width = (int)MODULE_CONTENT_HEADER_STORE_MODE_CONTENT_WIDTH;

          $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
          include 'includes/modules/content/cm_template.php';
        }
      }
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'VERSION_INSTALLED' => [
          'title' => 'Current Version',
          'value' => '1.0.8.8',
          'desc' => 'Version info. It is read only',
          'set_func' => 'cm_header_store_mode::readonly(',
        ],
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Header Store Mode Message Module',
          'value' => 'True',
          'desc' => 'Do you want to enable the Store Mode Message content module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '12',
          'desc' => 'What width container should the content be shown in?',
          'set_func' => "Config::select_one(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
        $this->config_key_base . 'CONTENT_ALIGN' => [
          'title' => 'Content Align',
          'value' => 'text-center',
          'desc' => 'How should the content be aligned?',
          'set_func' => "Config::select_one(['text-left', 'text-center', 'text-right'], ",
        ],
        $this->config_key_base . 'CHECKOUT_PAGES' => [
          'title' => 'Checkout Message Pages',
          'value' => 'shopping_cart.php',
          'desc' => 'The pages to show the Store Mode Message if in \"Checkout\" Mode.',
          'set_func' => "cm_header_store_mode::_edit_pages(",
          'use_func' => 'page_selection::_show_pages',
        ],
        $this->config_key_base . 'ACCOUNT_PAGES' => [
          'title' => 'Account Message Pages',
          'value' => 'shopping_cart.php;login.php',
          'desc' => 'The pages to show the Store Mode Message if in \"Account\" Mode.',
          'set_func' => "cm_header_store_mode::_edit_pages(",
          'use_func' => 'page_selection::_show_pages',
        ],
      ];
    }

// get offline time left
    private function get_offline_time() {
      if (MODULE_STORE_MODE_SHOW_BACK_TIME === 'False' ) {
        return  null;
      }

      $back_time = Text::is_empty(MODULE_STORE_MODE_BACK_TIME)
                 ? date('H:i', strtotime('now +2 hour'))
                 : MODULE_STORE_MODE_BACK_TIME;

      $time_left = null;
      if ( isset($back_time) && $back_time != '') {
        $to_time = time();
        $from_time = strtotime(date('Y-m-d') . $back_time . ':00');
        if ($from_time < $to_time) $from_time += 24 * 60 * 60;
        $time_left = gmdate("H:i", round(abs($to_time - $from_time)));
      }

      return  sprintf(MODULE_CONTENT_HEADER_STORE_MODE_ACCOUNT_REOPEN_TIME, $time_left);
    }

    public static function _edit_pages($values, $key) {
      $exclude_array = ['maintenance.php',
                        'index.php',
                        'download.php',
                        'redirect.php',
                        'ssl_check.php',
                        'opensearch.php'];

      $file_extension = pathinfo(Request::get_page(), PATHINFO_EXTENSION);
      $files_array = [];
      if ($dir = @dir(DIR_FS_CATALOG)) {
        while ($file = $dir->read()) {
          if ( !is_dir(DIR_FS_CATALOG . $file) && !in_array($file, $exclude_array) ) {
            if (pathinfo($file, PATHINFO_EXTENSION) == $file_extension) {
              $files_array[] = $file;
            }
          }
        }
        $dir->close();
        sort($files_array);
      }

      $values_array = explode(';', $values);

      $output = '';
      foreach ($files_array as $file) {
        $output .= '<br>' . (new Tickable($key . 'p_file[]', ['value' => $file], 'checkbox'))->tick(in_array($file, $values_array)) . '&nbsp;' . Text::output($file);
      }
      $output .= '<br>' . new Tickable($key . 'p_all', [], 'checkbox') . '&nbsp;' . TEXT_ALL;

      $output .= new Input('configuration[' . $key . ']', ['id' => $key . 'p_files'], 'hidden');

      $output .= '<script>
                  function ' . $key . 'p_update_cfg_value() {
                    var ' . $key . 'p_selected_files = \'\';

                    if ($(\'input[name="' . $key . 'p_file[]"]\').length > 0) {
                      $(\'input[name="' . $key . 'p_file[]"]:checked\').each(function() {
                        ' . $key . 'p_selected_files += $(this).attr(\'value\') + \';\';
                      });

                      if (' . $key . 'p_selected_files.length > 0) {
                        ' . $key . 'p_selected_files = ' . $key . 'p_selected_files.substring(0, ' . $key . 'p_selected_files.length - 1);
                      }
                    }

                    $(\'#' . $key . 'p_files\').val(' . $key . 'p_selected_files);
                  }

                  $(function() {
                    ' . $key . 'p_update_cfg_value();

                    if ($(\'input[name="' . $key . 'p_file[]"]\').length > 0) {
                      $(\'input[name="' . $key . 'p_file[]"]\').change(function() {
                        ' . $key . 'p_update_cfg_value();
                      });
                    }
                  });
                  $(\'input[name="' . $key . 'p_all"]\').click(function() {
                  var c = $(\'input[name^="' . $key . 'p_file"]\');
                    c.prop(\'checked\', !c.prop(\'checked\'));
                  });
                  </script>';

      return $output;
    }

    public static function readonly($value) {
      return $value;
    }

  }