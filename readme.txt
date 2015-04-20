=== WP SEO HTML Sitemap ===
Contributors: riseofweb
Donate link: http://riseofweb.com/
Tags: WPSEO, Yoast SEO, HTML Sitemap, Sitemap
Requires at least: 3.5
Tested up to: 4.1
Stable tag: 0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
== Description ==

A responsive HTML sitemap shortcode that uses all of the settings for your XML sitemap in the WordPress SEO by Yoast Plugin.

Use the shortcode: [wpseo_html_sitemap]

Want to see the plugin in action? [Live HTML Sitemap Example](https://riseofweb.com/sitemap/).

Note: The [WordPress SEO by Yoast plugin](https://wordpress.org/plugins/wordpress-seo/) is NOT required in order to use this plugin. But this plugin does take full advantage of all settings related to the XML sitemap settings.

Known oversights to address in future versions:
- Author Roles filtering, I do not have the it setup to be able to filter out author roles.
- The posts are sorted by name and may not show if a specific Category is selected to not show in the sitemap XML.

Possible Future Version Features To Add (or possibly to a premium version of the plugin):
- Backend interface with settings
- Custom CSS editing
- Style templates to choose from
- Adding more than 2 columns
- Custom Header names
- Adding custom URLs

== Installation ==

1. Upload folder to '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

Is the page responsive?
Yes.

Can I change the CSS?
Yes, the CSS is prefixed by the div id "#wpseo_sitemap". If you want to override any of the CSS just use the ">" example "#wpseo_sitemap > div > h3{}



== Changelog ==

= 0.5 =
* Inital Release
