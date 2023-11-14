<?php
/*
  $Id: st_store_mode.php
  $Loc: catalog/includes/modules/header_tags/

  Store Mode 1.6.0
  by @raiwa
  raiwa@phoenixcartaddons.com
  www.phoenixcartaddons.com
  
  updated for Phoenix Pro by @ecartz

  Copyright (c) 2021, Rainer Schmied
  All rights reserved.

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class st_store_mode extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_STORE_MODE_';

    public function __construct() {
      parent::__construct(__FILE__);

      $this->description .= '<p>by @raiwa <u><a target="_blank" href="http://www.phoenixcartaddons.com">www.phoenixcartaddons.com</a></u></p>';
      if (!file_exists(DIR_FS_CATALOG . '/includes/hooks/shop/system/storeMode.php')) {
        $this->enabled = false;
        $this->description = '<div class="alert alert-danger" role="alert">' .
                                 MODULE_STORE_MODE_HOOK_WARNING .
                             '</div>' .
                             $this->description;
      }

      if ( defined('MODULE_STORE_MODE_STATUS') ) {
        $allowed_ips_array = explode(',', MODULE_STORE_MODE_ALLOWED_IPS);
        foreach ($allowed_ips_array as $allowed_ip) {
          $allowed_ip = trim($allowed_ip);
          if (!filter_var($allowed_ip, FILTER_VALIDATE_IP)) {
            $this->description = '<div class="alert alert-danger" role="alert">' .
                                    sprintf(MODULE_STORE_MODE_IP_ERROR, $allowed_ip) .
                                 '</div>' .
                                 $this->description;
          }
        }
      }
    }

    function execute() {

    }

    protected function get_parameters() {
      $config_parameters = [
        static::CONFIG_KEY_BASE . 'VERSION_INSTALLED' => [
          'title' => 'Current Version',
          'value' => '1.5.0',
          'desc' => 'Version info. It is read only',
          'set_func' => 'st_store_mode::readonly(',
        ],
        static::CONFIG_KEY_BASE . 'STATUS' => [
          'title' => 'Enable Store Mode Module',
          'value' => 'True',
          'desc' => 'Do you want to add the Store Mode Module to your shop?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        static::CONFIG_KEY_BASE . 'MODE' => [
          'title' => 'Store Mode',
          'value' => 'online',
          'desc' => 'Test Modes: Only allowed IPs will be redirected.',
          'set_func' => "Config::select_one(['online', 'custom', 'custom-test', 'checkout', 'checkout-test', 'account', 'account-test', 'offline', 'offline-test'], ",
          'use_func' => 'st_store_mode::add_to_htaccess',
        ],
        static::CONFIG_KEY_BASE . 'LOGOFF' => [
          'title' => 'Logoff customers',
          'value' => 'True',
          'desc' => 'Do you want to logoff customers? Applies on all modes except online.',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        static::CONFIG_KEY_BASE . 'SHOW_BACK_TIME' => [
          'title' => 'Show back time',
          'value' => 'True',
          'desc' => 'Do you want to show the back time message? Applies to the maintenance page shown in offline mode.',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        static::CONFIG_KEY_BASE . 'BACK_TIME' => [
          'title' => 'Back hour',
          'value' => '0:00',
          'desc' => 'The hour when the store will be back online.<br>Format: \"hh:mm\", Example: 19:30.<br> Leave empty to automatically set 2 h ahead from the current time when disconnected.<br>Limit: 24 h, next day\'s time will show correct.<br>Leave empty to disable this message part.',
        ],
        static::CONFIG_KEY_BASE . 'ALLOWED_IPS' => [
          'title' => 'Allowed IPs',
          'value' => getenv('REMOTE_ADDR'),
          'desc' => 'List of allowed IPs to access the store if in Closed or Maintenance Mode?<br>Coma separated list. Format: 000.000.000.000,000.000.000.000.',
        ],
        static::CONFIG_KEY_BASE . 'CUSTOM_PAGES' => [
          'title' => 'Custom Pages',
          'value' => 'product_reviews.php;product_reviews_write.php',
          'desc' => 'The custom pages to disallow.',
          'set_func' => "st_store_mode::_edit_pages(",
          'use_func' => 'page_selection::_show_pages',
        ],
        static::CONFIG_KEY_BASE . 'CHECKOUT_PAGES' => [
          'title' => 'Checkout Pages',
          'value' => 'checkout_shipping.php;checkout_shipping_address.php;checkout_payment.php;checkout_payment_address.php;checkout_confirmation.php;checkout_process.php',
          'desc' => 'The checkout pages to disallow.',
          'set_func' => "st_store_mode::_edit_pages(",
          'use_func' => 'page_selection::_show_pages',
        ],
        static::CONFIG_KEY_BASE . 'LOGIN_ACCOUNT_PAGES' => [
          'title' => 'Login Account',
          'value' => 'login.php;create_account.php;account.php;account_edit.php;account_history.php;account_history_info.php;account_newsletters.php;account_notifications.php;account_password.php;address_book.php;address_book_process.php;password_forgotten.php;password_reset.php',
          'desc' => 'The login and account pages to disallow.',
          'set_func' => "st_store_mode::_edit_pages(",
          'use_func' => 'page_selection::_show_pages',
        ],
        static::CONFIG_KEY_BASE . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Must be first, readonly.',
          'set_func' => 'st_store_mode::readonly(',
        ],
      ];
      $lng = new language;
      foreach($lng->catalog_languages as $id => $value) {
      	$key = strtoupper($value['directory']);
        switch ($key) {
        	case 'ESPANOL':
            $config_parameters[static::CONFIG_KEY_BASE . 'MESSAGE_' . $key] = [
                'title' => 'Mensaje para admins ' . $key,
                'value' => 'La tienda está en modo <i>%s</i>. No olvide volver a ponerla en Modo Online cuando haya terminado.<br>El mensaje de tiempo para volver Online mostrada es <i>%s</i>.',
                'desc' => 'Mensaje que se muestra a Administradores cuando la tienda no está en Modo Online en idioma ' . $key,
            ];
       		break;
        	case 'GERMAN':
            $config_parameters[static::CONFIG_KEY_BASE . 'MESSAGE_' . $key] = [
                'title' => 'Mitteilung für Admins ' . $key,
                'value' => 'Der Shop befindet sich im <i>%s</i> Modus. Vergessen Sie nicht ihn wieder auf Online Modus zu schalten wenn Sie fertig sind.<br>Die angezeigte Online Zeit Mitteilung ist <i>%s</i>.',
                'desc' => 'Mitteilung die für Administratoren angezeigt wird wenn der Shop nicht in Online Modus ist auf ' . $key,
            ];
            break;
        	default:
            $config_parameters[static::CONFIG_KEY_BASE . 'MESSAGE_' . $key] = [
                'title' => 'Message for admins ' . $key,
                'value' => 'The store is actually in <i>%s</i> Mode. Don\'t forget to switch it back to Online Mode once you finished.<br>The time message shown to come back Online is: <i>%s</i>.',
                'desc' => 'Message shown to Administrators if the store is not in Online Mode for ' . $key . ' language',
            ];
        }
      }

      return $config_parameters;
    }

    function install($parameter_key = null) {

      parent::install($parameter_key);

      st_store_mode::htaccess_backup('master_backup_htaccess_' . date('Y-m-d'));

      // customize maintenance.php
      if ( is_file(DIR_FS_CATALOG . 'maintenance.php') ) {
        $file = file_get_contents(DIR_FS_CATALOG . 'maintenance.php');
      // add store name
        $store_name_esc = str_replace('\'', '\\\'', STORE_NAME);
        $file = preg_replace('%const TEXT_STORE_NAME = \'(.*)\';%', 'const TEXT_STORE_NAME = \'' . $store_name_esc . '\';', $file);
      // add store mail
        $file = preg_replace('%\$store_mail = \'(.*)\';%', '\$store_mail = \'' . STORE_OWNER_EMAIL_ADDRESS . '\';', $file);
      // add store time zone
        $file = preg_replace('%date_default_timezone_set((.*));%', 'date_default_timezone_set(\'' . date_default_timezone_get() . '\');', $file);
        file_put_contents(DIR_FS_CATALOG . 'maintenance.php', $file);
      }
    }

    public function remove() {
      parent::remove();

      // remove rewrite rules
      $file = file_get_contents(DIR_FS_CATALOG . '.htaccess');
      if ( strpos($file, 'RewriteRule .* maintenance.php') > 0 ) {
        $file = preg_replace('@#Store Mode Begin((?:.|[\r\n])+?)#Store Mode End\n\n@', '', $file);
        file_put_contents(DIR_FS_CATALOG . '.htaccess', $file);
      }

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
        sort($files_array);
        $dir->close();
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

    public static function add_to_htaccess($mode_text) {
      if ( isset($_GET['module']) && $_GET['module'] == 'st_store_mode') {
        // update the allowed IPs list
        $allowed_ips_array = [];
        $offline_rewrite = null;
        $allowed_ips_array = explode(',', MODULE_STORE_MODE_ALLOWED_IPS);
        $allowed_ips_array_cleaned = [];
        foreach ($allowed_ips_array as $allowed_ip) {
          $allowed_ip = trim($allowed_ip);
          if (filter_var($allowed_ip, FILTER_VALIDATE_IP)) {
            $allowed_ips_array_cleaned[] = $allowed_ip;
          }
        }
        $allowed_ips_array = $allowed_ips_array_cleaned;
        if (!in_array(getenv('REMOTE_ADDR'), $allowed_ips_array)) {
          $ip_list = getenv('REMOTE_ADDR') . ',' . MODULE_STORE_MODE_ALLOWED_IPS;
          $GLOBALS['db']->query("update `configuration` set `configuration_value` =  '" . $ip_list . "' where `configuration_key` = 'MODULE_STORE_MODE_ALLOWED_IPS'");
          $allowed_ips_array = explode(',', $ip_list);
        }
        // define the .htaccess rewrite rules
        $back_time = '';
        if (MODULE_STORE_MODE_SHOW_BACK_TIME != 'false' ) {
          if (MODULE_STORE_MODE_BACK_TIME != '') {
            $back_time = '?back_time=' . MODULE_STORE_MODE_BACK_TIME;
          } else {
            $back_time = '?back_time=' . date('H:i', strtotime('now +2 hour'));
          }
        }
        if ( strpos(MODULE_STORE_MODE_MODE, 'offline') > -1 ) {
          $offline_rewrite = '#Store Mode Begin' . "\n" .
                             '<IfModule mod_rewrite.c>' . "\n" .
                             ' RewriteEngine on'  . "\n" .
                             ' RewriteBase ' . DIR_WS_CATALOG . "\n" .
                             ' RewriteRule ^' . str_replace(DIR_WS_CATALOG, '', DIR_WS_ADMIN) . ' - [L,NC]' . "\n";
          $i = null;
          foreach ($allowed_ips_array as $ip) {
            if (MODULE_STORE_MODE_MODE == 'offline-test') { // define the allowed IPs redirect rules for test mode
              $i++;
              $offline_rewrite .= ' RewriteCond %{REMOTE_ADDR} ^' . str_replace('.', '\.', $ip) . '$' . ($i < count($allowed_ips_array)? '  [OR]' : '') . "\n";
            } elseif (MODULE_STORE_MODE_MODE == 'offline') {// define the allowed IPs exclude redirect rules for normal mode
             $offline_rewrite .= ' RewriteCond %{REMOTE_ADDR} !^' . str_replace('.', '\.', $ip) . '$' . "\n";
            }
          }
          $offline_rewrite .= ' RewriteCond %{REQUEST_URI} !maintenance.php$ [NC]' . "\n" .
                              ' RewriteCond %{REQUEST_URI} !.(jpe?g?|png|gif|css|js) [NC]' . "\n" .
                              ' RewriteRule .* maintenance.php' . $back_time. ' [R=307,L]' . "\n" . //VMN
                              '</IfModule>' . "\n" .
                              '#Store Mode End' . "\n\n";
          // update the .htaccess file
          $file = file_get_contents(DIR_FS_CATALOG . '.htaccess');
          if ( strpos($file, 'RewriteRule .* maintenance.php') === false ) {
            st_store_mode::htaccess_backup('backup_htaccess');
          } elseif ( strpos($file, 'RewriteRule .* maintenance.php') > 0 ) {
            $file = preg_replace('@#Store Mode Begin((?:.|[\r\n])+?)#Store Mode End\n\n@', '', $file);
          }
          $file = preg_replace('@ExpiresDefault@', '#ExpiresDefault', $file);   //VMN
          $content = $offline_rewrite . $file;
          file_put_contents(DIR_FS_CATALOG . '.htaccess', $content);
        } elseif ( (strpos(MODULE_STORE_MODE_MODE, 'offline') === false ) && isset($_GET['module']) && $_GET['module'] == 'st_store_mode' ) {
          // remove rewrite rules
          $file = file_get_contents(DIR_FS_CATALOG . '.htaccess');
          $file = preg_replace('@#Store Mode Begin((?:.|[\r\n])+?)#Store Mode End\n\n@', '', $file);
          $file = preg_replace('@#ExpiresDefault@', 'ExpiresDefault', $file);   //VMN
          file_put_contents(DIR_FS_CATALOG . '.htaccess', $file);
        }
      }
      return nl2br(implode("\n", explode(';', $mode_text)));
    }

     // function create htaccess backup
    public static function htaccess_backup($bkup_name) {
      $separator = ((substr(DIR_FS_CATALOG, -1) != '/') ? '/' : '');
      $backupDir = DIR_FS_CATALOG . $separator . 'htaccess_backups/';

      // create backup dir
      if(!is_dir($backupDir)) mkdir($backupDir, 0755);

      // create .htaccess protection containing allowed ip's
      $htaccessfile = $backupDir . '.htaccess';
      $htaccess = '';
      if ( defined('MODULE_STORE_MODE_ALLOWED_IPS') && !Text::is_empty(MODULE_STORE_MODE_ALLOWED_IPS) ) {
        $allowed_ips_array = explode(',', MODULE_STORE_MODE_ALLOWED_IPS);
        foreach ($allowed_ips_array as $ip) {
          $htaccess .= 'Require ip ' . $ip . "\n";
        }
      }
      file_put_contents($htaccessfile, $htaccess);

      $htaccessfileOrig = DIR_FS_CATALOG . $separator . '.htaccess';
      $htaccessfileBkup = $backupDir . $bkup_name;

      $result = copy($htaccessfileOrig, $htaccessfileBkup);
  }

} // end class
