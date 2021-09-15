=== dotdigital for WooCommerce ===
Contributors: dotMailer, amucklow, fstrezos
Requires at least: 4.7
Tested up to: 5.8
Requires PHP: 7.0
WC requires at least: 3.3
WC tested up to: 5.6
Stable tag: 1.2.0
License: MIT
License URI: https://opensource.org/licenses/MIT

Connect your WooCommerce store to dotdigital and put customer, subscriber, product and order data at your fingertips.


== Description ==

dotdigital is a marketing automation platform that provides brands globally with the tools they need to acquire, convert, and retain customers. Our plug and play connector lets you sync your data from your store to your dotdigital account to empower your marketing. dotdigital gives you all the tools you need to send on-brand emails, automate campaigns, and manage mailing lists to engage with your audience.

* Sync all your contacts to dotdigital, and segment them for your cross-channel campaigns.
* Use your customers' order history and browsing behavior to better target your audience.
* Build and automate lifecycle programs to engage your customers and drive revenue.

If you're not a dotdigital user already you can find out more about us at <a href="https://www.dotdigital.com">dotdigital.com</a>.

Once you've set up your integration, you can build automated journeys with data-driven marketing programs. Boost your ROI with abandoned cart, AI-powered product recommendations, advanced personalization, social re-targeting, and cross-channel marketing automation programs.

dotdigital for WooCommerce will help you:
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

* When you activate, deactivate or uninstall the plugin, we send a plugin id (a random string that we create) to dotdigital. This allows dotdigital to identify your website.
* To connect to dotdigital, you install some additional files (see below) to create a 'bridge' with some middleware called <a href="https://api2cart.com/">API2Cart</a>.
* Once connected, dotdigital syncs data from your website to the platform via API2Cart, using your plugin id to confirm it's you.

### Disclaimer

The software made available to you is provided "as is" without warranty of any kind, either expressed or implied and such software is to be used at your own risk and without modification.

== Installation ==

Follow these steps:

1. Download the latest master branch plugin code from <a href="https://github.com/dotmailer/dotdigital-for-woocommerce">Github</a>.
2. Copy and unzip the downloaded plugin file into your WordPress plugin folder. IMPORTANT: the folder must be named ‘dotdigital-for-woocommerce’.
3. Log into your WordPress admin console.
4. In the left-hand menu, go to Plugins > Installed Plugins.
5. Find 'dotdigital for WooCommerce'.
6. Click on Activate Plugin.
7. In the left-hand menu, click on dotdigital for WooCommerce.
8. Log into dotdigital.
  * You'll see the 'Almost there!' message, outlining the three final steps to complete:
  * Download the bridge zip file (Disclaimer above also applies)
  * Copy and unzip the bridge file into your WordPress root folder
  * Click on 'Test connection' to check all's well

The store should now be connected and dotdigital will start syncing customer data automatically.

For more detailed information on installation, please see our <a href="https://support.dotdigital.com/hc/en-gb/categories/201643998-Integrations">support documentation</a>.

== Changelog ==

= 1.2.0 =

**What’s new**

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
