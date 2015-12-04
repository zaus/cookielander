=== Forms: 3rd-Party Submission Reformat ===
Contributors: zaus
Donate link: http://drzaus.com/donate
Tags: contact form, form, contact form 7, CF7, gravity forms, GF, CRM, mapping, 3rd-party service, services, remote request, reformat, date format, field format
Requires at least: 3.0
Tested up to: 4.3
Stable tag: trunk
License: GPLv2 or later

Customize specific Forms-3rdparty submission fields' format.

== Description ==

Allows you to customize the formatting of specific submission fields before mapping to a 3rdparty service endpoint with [Forms 3rdparty Integration](http://wordpress.org/plugins/forms-3rdparty-integration/).

For example, can be used to reformat a Gravity Form or Contact Form 7 'date' field before sending it to a CRM.

== Installation ==

1. Unzip/upload plugin folder to your plugins directory (`/wp-content/plugins/`)
2. Make sure [Forms 3rdparty Integration](http://wordpress.org/plugins/forms-3rdparty-integration/) is installed and settings have been saved at least once.
3. Activate this plugin
4. Choose which fields to reformat, as they appear in the 'mapping' column
5. Provide one or more regular expression patterns to find and replace, like `/(\d+)\/(\d+)\/(\d+)/` to change the date from 'dd/mm/yyyy'
6. Provide one or more regular expression replacement patterns to replace, like `$2-$1-$3` to change the date to 'mm-dd-yyyy'

== Frequently Asked Questions ==

= How do I write a regex? =

Sorry, you'll have to learn that the hard way...

= It doesn't work right... =

Drop an issue at https://github.com/zaus/forms-3rdparty-submission-format

== Screenshots ==

N/A.

== Changelog ==

= 0.2 =
* refactored to generic, settings-enabled

= 0.1 =
* targeting specific fields and replacement formats

== Upgrade Notice ==
