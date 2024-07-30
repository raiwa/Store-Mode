# Store Mode
---------------------------------

Compatibility: Phoenix Cart 1.0.9.4+
Tested with Phoenix 1.0.9.4
PHP 7.0-8.3


## WHAT IS IT?

This add-on allows to switch your store to different closed/maintenance modes. It allows you to do
installations, maintenance and checks closing only the strict necessary areas of your store.

### Store modes:

#### Online:
Normal store mode

#### Custom:
Customizable group of pages. Default: product_reviews.php and product_reviews_write.php.
Any group of pages can be defined. Customers will be redirected to the last visited page outside the
restricted area or index.php.
A temporary message will be shown only if a customer tries to access the restircted area.

#### Checkout:
By default all checkout pages are included in this group. Customers will be redirected to the last visited
page outside the restricted area or shopping_cart.php.
A permanent message will be shown on the shopping cart page and optional on the index page. An
additional temporary message shows only if a customer tries to access the restircted area.

#### Account:
By default all checkout pages and account pages are included in this group. Customers will be redirected to
the last visited page outside the restricted area or index.php.
A permanent message will be shown on the shopping cart page and index page. An additional temporary
message shows only if a customer tries to access the restircted area.

#### Offline:
Customers will be redirected by .htaccess rewrite rules to a standalone 503 maintenance page.
The store administrators IP will be entered automatically in a configuration list. Additional
administrators/developpers IPs can be added.
These IPs are excluded from redirects in all the above listed Modes.
Test Modes for all above listed modes are available which will only redirect the IP's included in the
configuration list. This can be used to check the redirects and messages.

## HOW TO INSTALL

0. Back up your store and database
Even there are no file changes, back up at least your main (root) .htaccess file, which will be modified by the 
header tag module when switching to offline or offline-test mode.
1. Copy the new files into the appropriate folders
2. Edit: maintenance.php
   1. Comment/uncomment the languages you wish to show
Lines 15-18
If you need other languages, just replace one of the unused language definition with your language
translation.
   2. Optional comment the Store Mail if you do not wish to show the "Contact" E-Mail
link.
Line 37  
NOTE: Do not add any external resources like images/store logo to this page. This page must not require
any additional resource of the store installation to ensure it will show correct in any situation.
4. In Admin =>Modules => Content[header] install and configure the module 'Store
Mode Message'.
5. In Admin =>Modules => Store Mode install and configure the module 'Store Mode'.
6. Customize the language definitions of the messages
   1. Permanent messages for Checkout and Account Modes:
/includes/languages/mylanguage/modules/content/header/cm_header_store_mode.php
   2. Temporary Message for Custom Mode:
/includes/languages/mylanguage/modules/store/st_store_mode.php
   3. Reminder Messages in Admin and Store for Administrators:
Admin => Modules => Store => Store Mode => Message for admins ENGLISH [and other store languages]

===========
That's it.
===========  
