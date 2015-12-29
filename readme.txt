=== Cookielander ===
Contributors: zaus
Donate link: http://drzaus.com/donate
Tags: contact form, ppc, landing page, cookies, landing referrer, referral
Requires at least: 3.0
Tested up to: 4.3
Stable tag: trunk
License: GPLv2 or later

Save referral variables to temporary storage (cookies)

== Description ==

Allows you to specify which querystring, post, header, or cookie parameters to save to temporary storage (session, another cookie, header) for use elsewhere in the site.  Great for capturing referral variables (GET querystring parameters, headers) and reusing them in contact forms, CRMs, etc.

== Installation ==

1. Unzip/upload plugin folder to your plugins directory (`/wp-content/plugins/`)
2. Activate this plugin
3. Determine which referral variables to look for:
   * in the querystring or post ("request")
   * in headers
   * in cookies
4. Determine where to save those variables:
   * in the session
   * in a cookie
   * in a header
5. If saving to a cookie, you may:
	* leave the destination key blank to reuse the source key
	* specify 'name', 'path', 'domain', 'expires', etc by entering them as url-querystring format corresponding to the [`setcookie` parameters](http://php.net/manual/en/function.setcookie.php).  ex `name=foobar@expires=700000`.

== Frequently Asked Questions ==

= It doesn't work right... =

Drop an issue at https://github.com/zaus/cookielander

== Screenshots ==

N/A.

== Changelog ==

= 0.4 =
* dynamic UI
* reads from bunch of sources
* saves to a bunch of destinations

= 0.1 =
* started

== Upgrade Notice ==
