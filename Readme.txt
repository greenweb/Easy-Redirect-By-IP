===Easy Bouncer - Redirect by IP===
Contributors: Greenweb
Donate link: http://www.beforesite.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: beforesite, redirect, redirection by IP address, site redirection, website redirection, private website
Requires at least: 3.5
Tested up to: 3.5.9
Stable tag:1

Easily redirect visitors to another web address if their IP address is not on a safe list. Give users the passkey web address they will be added to the "safe IP list" allowing them to view the website (see plugin options)

== Description ==

On activation of this plugin your IP address will be added to a safe list. 

You should immediately go to the plugin's settings page to set up a passkey. 

*Bookmark the passkey's web address ASAP!*

The passkey is useful if you have to access your website from a different location or if you want to share the website with a third-party.

Once a user visits the website with the passkey web address they will be added to the "safe IP list" allowing them to view the website.

These IP addresses can be removed or added to via the setting page.

At the options page you can set up the redirect URL. 

Leaving the redirect URL blank will display a access denied message to anybody trying to visit the website.

This project is mirrored at GitHub https://github.com/greenweb/Easy-Redirect-By-IP please stop by if you feel like submitting a bug or contributing. 

= Main Functions =

301 Redirection to the web address that you specify.

= Warning =

Do not redirect the user to a page with in the __same WordPress website__ this will cause an infinite loop.

Thanks to the *Query Tags Input Plugin* used in our admin area and is licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php 

Documentation for this jQuery plugin lives here: http://xoxco.com/projects/code/tagsinput/

== Installation == 

* Upload the plugin's folder to the /wp-content/plugins/ directory
* Activate the plugin through the 'Plugins' menu in WordPress
* Change the options under tools

== Frequently Asked Questions ==

* Q: Can I redirect to a web page on the same domain? 
 * A: No, this would create an infinite loop

* Q: My IP address has changed now I can't access my site
 * A: If you bookmarked your passkey url you can use that - other wise you'll need to FTP up to your server and delete or rename this plugin's folder:

**/wp-content/plugins/easy-redirect-by-ip/**

== Upgrade Notice ==

  N/A

== Screenshots ==

1. Options Page

== Changelog ==

= 1.0 =

 * Beta Launch