===Easy Redirect by IP===
Contributors: Greenweb
Donate link: http://www.beforesite.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: beforesite
Requires at least: 3.5
Tested up to: 3.5.9
Stable tag: 0.1

Plugin redirects everyone except the admin's IP and addresses approved via passkey to a URL specified in the settings page.

== Description ==
On activation of this plugin your IP address will be added to a safe list. 
You should immediately go to the plugin's settings page to set up a passkey.
This passkey is used to create a web address that will when visited add the visitor's IP address to the safe IP list. Allowing them to view the website. These IP addresses can be removed, and added to, via the setting page.
Very useful if you are logging into your website from a different location or if you want to share the website with a third-party.
At the options page you can set up the redirect URL. Leaving this blank will display a access denied message to anybody trying to visit the website.

= Main Functions =
 * 301 Redirection to the web address that you specify.
[More Info and Support](http://www.beforesite.com/)
= Waring =
 * Do not redirect the user to a page with in the __same WordPress website__ or their will be a redirect loop and the sky will fall! _(Maybe not the last one)_. 
== Installation == 
 * Upload the plugin's folder to the /wp-content/plugins/ directory
 * Activate the plugin through the 'Plugins' menu in WordPress
 * Change the options under tools
== Frequently Asked Questions ==
  [FAQ](http://www.beforesite.com/)
== Upgrade Notice ==
  N/A
== Screenshots ==
1. Options Page
== Changelog ==
= 0.1 =
 * Beta Launch