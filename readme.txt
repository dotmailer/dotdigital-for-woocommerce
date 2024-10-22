=== Dotdigital for WooCommerce ===
Contributors: dotMailer, amucklow, fstrezos, pvpcookie
Requires at least: 5.7
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.4.4
License: MIT
License URI: https://opensource.org/licenses/MIT

Connect your WooCommerce store to Dotdigital and put customer, subscriber, product and order data at your fingertips.


== Description ==

Dotdigital is a marketing automation platform that provides brands globally with the tools they need to acquire, convert, and retain customers. Our plug and play connector lets you sync your data from your store to your Dotdigital account to empower your marketing. Dotdigital gives you all the tools you need to send on-brand emails, automate campaigns, and manage mailing lists to engage with your audience.

* Sync all your contacts to dotdigital, and segment them for your cross-channel campaigns.
* Use your customers' order history and browsing behavior to better target your audience.
* Build and automate lifecycle programs to engage your customers and drive revenue.
* Subscribe customer and guest mobile numbers to SMS marketing, with consent data.

If you're not a Dotdigital user already you can find out more about us at <a href="https://www.dotdigital.com">dotdigital.com</a>.

Once you've set up your integration, you can build automated journeys with data-driven marketing programs. Boost your ROI with abandoned cart, AI-powered product recommendations, advanced personalization, social re-targeting, and cross-channel marketing automation programs.

Dotdigital for WooCommerce will help you:
* Inspire customer engagement with beautifully designed email campaigns
* Identify valuable customers with multiple segment templates and a fully flexible segment builder
* Reach niche audiences with hyper-targeted landing pages, surveys, and forms
* Automate lifecycle campaigns based on customer behavior across email, SMS, social, push, and more.
* Encourage repeat sales with replenishment programs
* Optimize every customer touchpoint, from account creation to post-purchase with transactional emails
* Win back lapsed shoppers with abandoned cart campaigns
* Re-target customers who are browsing your store and showing high purchase intent
* Sell more with predictive product recommendations in email and on your store
* Keep an eye on your most important store KPIs with commerce intelligence dashboards
* And much more

### How the plugin works

* When you activate, deactivate or uninstall the plugin, we send a plugin id (a random string that we create) to dotdigital. This allows Dotdigital to identify your website.
* To connect to dotdigital, you install some additional files (see below) to create a 'bridge' with some middleware called <a href="https://api2cart.com/">API2Cart</a>.
* Once connected, Dotdigital syncs data from your website to the platform via API2Cart, using your plugin id to confirm it's you.

### Disclaimer

The software made available to you is provided "as is" without warranty of any kind, either expressed or implied and such software is to be used at your own risk and without modification.

== Installation ==

Follow these steps:

1. Log into your WordPress admin console.
2. In the left-hand menu, go to Plugins.
3. Select 'Add New'.
4. Find 'Dotdigital for WooCommerce'.
5. Click on 'Install Now' then 'Activate'.
6. In the left-hand menu, click on Dotdigital for WooCommerce.
7. Log into dotdigital.
  * You'll see the 'Almost there!' message, outlining the three final steps to complete:
  * Download the bridge zip file (Disclaimer above also applies)
  * Copy and unzip the bridge file into your WordPress root folder
  * Click on 'Test connection' to check all's well

The store should now be connected and Dotdigital will start syncing customer data automatically.

For more detailed information on installation, please see our <a href="https://support.dotdigital.com/hc/en-gb/categories/201643998-Integrations">support documentation</a>.

== Changelog ==

= 1.4.4 =

**Bug fixes**
- The plugin no longer (incorrectly) shows as incompatible with WooCommerce HPOS.
- List ids are now sent as integers in the V3 API request.

= 1.4.3 =

**Improvements**
- We added `totalprice_incl_tax` to cart insight line items.

= 1.4.2 =

**Bug fixes**
- We fixed a problem with WP CLI breaking in a composer-based WordPress.

= 1.4.1 =

**Bug fixes**
- We fixed the missing domain in the `load_plugin_textdomain` function.

= 1.4.0 =

**What's new**
- Customers and guests can now sign up for SMS marketing, with consent, at registration and checkout.
- SMS subscribers are pushed to a specific list in Dotdigital via the new V3 API.
- Mobile numbers are validated client-side before submit.
- The plugin’s vendor namespaces are now prefixed before SVN submission, to prevent namespace clashes.

**Improvements**
- We added a filter to enable modification of the cart item data before sending to Dotdigital. <a href="https://github.com/dotmailer/dotdigital-for-woocommerce/pull/26">External contribution</a>
- We now update cart insight when modifying item quantities in the basket. <a href="https://github.com/dotmailer/dotdigital-for-woocommerce/pull/25">External contribution</a>

**Bug fixes**
- We added some extra checks for `WC()→customer` and `WC()→session` before calling functions on those objects.

= 1.3.4 =

**Improvements**
- We added compatibility with WooCommerce’s new HPOS order storage feature.

**Bug fixes**
- We fixed an error related to products not being found for items in the cart.

= 1.3.3 =

**Bug fixes**
- We fixed an error on product pages caused by passing `null` to `round()` in PHP 8.1.
- Cypress was upgraded to fix a dependency alert.

= 1.3.2 =

**What's new**
- We now support WordPress up to v6.0 and Woocommerce up to v6.5.1.

**Bug fixes**
- The salePrice property in cartInsight line items now shows the regular price if there is no discount.

= 1.3.1 =

**Bug fixes**
- We fixed a compatibility issue with the WooCommerce GlobalE PRO Integration plugin.
- Cypress upgrade to fix vulnerability.

= 1.3.0 =

**What's new**
- The connector now supports abandoned cart program enrolment for customers and guests. For this update, abandoned cart configuration is managed via **Dotdigital for Woocommerce > Settings**.
- We have integrated web behavior tracking into the plugin. Abandoned browse is also now supported.

**Improvements**
- We've added Cypress test coverage for our plugin configuration.
- We've updated the naming of Dotdigital throughout the connector.

= 1.2.1 =

* Escape variables to improve security in admin and widget classes.
* Rename PLUGIN_DIR_PATH.
* Use sanitize_email in place of sanitize_text_field for email input.

= 1.2.0 =

**What's new**

* We now support guest subscriptions. All subscribers are stored in their own database table, with rows created and updated via the checkboxes at user registration and checkout.
* We’ve added a script to migrate existing customer subscribers (identified by a key in `wp_usermeta`) into the new table.
* The plugin now ships with a configurable newsletter signup widget (note this is a ‘Legacy widget’ as of WordPress 5.8).
* There is a new settings area to manage the display of marketing checkboxes and other settings.
* Merchants can check a box to enable site and ROI tracking.
* The plugin name has been changed in line with company branding.

**Improvements**

* The marketing checkbox displayed at checkout has been moved to after the WooCommerce billing form, for a cleaner two-column layout.
* The plugin code now adheres to WordPress coding standards.
* The plugin now stores a version in the options table at install and upgrade.

= 1.1.1 =
* Fix for store names containing special characters

= 1.1.0 =
* Abandoned cart support

= 1.0.0 =
* First release
* Sync WooCommerce customers
* Sync WooCommerce order history
* Sync WooCommerce product catalog
* Shows newsletter subscription checkbox (\_wc_subscribed_to_newsletter) in registration & checkout pages
