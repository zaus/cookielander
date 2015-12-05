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

Allows you to specify which querystring parameters to save to temporary storage for use elsewhere in the site.  Great for capturing referral variables (GET querystring parameters, headers) and reusing them in contact forms, CRMs, etc.

== Installation ==

1. Unzip/upload plugin folder to your plugins directory (`/wp-content/plugins/`)
2. Make sure [Forms 3rdparty Integration](http://wordpress.org/plugins/forms-3rdparty-integration/) is installed and settings have been saved at least once.
3. Activate this plugin
4. Determine which what referral variables to look for:
   * in the querystring
   * in headers
5. List them out in JSON format, like

    [
      { 'get': 'url-parameter-1', 'cookie': null },
      { 'get': 'url-parameter-2', 'cookie': 'some-other-name' },
      { 'header': 'x-referral', 'cookie': 'crm.xref' },
      { 'get': 'ref', 'cookie': 'crm.ref' },
    ]

6. The above will save:
   * the querystring parameter (like `?url-parameter-1=VALUE`) to a cookie of the same name
   * the querystring parameter `url-parameter-2` to a cookie named `some-other-name`
   * the request header `x-referral` to a cookie named `crm` whose value is an array, at key `xref`
   * the querystring parameter `ref` to the same cookie above at key `ref`

== Frequently Asked Questions ==

= It doesn't work right... =

Drop an issue at https://github.com/zaus/cookielander

== Screenshots ==

N/A.

== Changelog ==

= 0.1 =
* started

== Upgrade Notice ==
