=== WP Geo Distances ===
Contributors: husobj
Donate link: http://www.wpgeo.com/donate
Tags: wp-geo, distance, distances, travel, travelling
Requires at least: 3.1
Tested up to: 3.6.1
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a shortcode and functions to calculate distances between locations plotted using the WP Geo plugin.

== Description ==

Adds a shortcode and functions to calculate distances between locations plotted using the WP Geo plugin.

The main functionality of this plugin revolves around the WPGeo_Distance_Query class. You can perform queries in the same way as using WP_Query and use the get_distance() method to retrieve the distance between the queries posts. For example:

`
$distance_query = new WPGeo_Distance_Query();  
$distance = $distance_query->get_distance();
`

You can use the `wpgeo_distance` shortcode to display the distance in your posts. It currently accepts 2 optional arguments:

* **round** : The number of decimal places to show.
* **unit** : By default distances are calculated in km. You can specify `miles` instead.

At the moment the shortcode only returns the distance between ALL posts.

== Installation ==
1. Download the archive file and uncompress it.
2. Put the "wp-geo-distances" folder in "wp-content/plugins"
3. Enable in WordPress by visiting the "Plugins" menu and activating it.

== Frequently Asked Questions ==

= How can I use a shortcode in a widget? =
Try the [Shortcode Widget](http://wordpress.org/plugins/shortcode-widget/) plugin.

== Changelog ==

= 0.1 =

* First release.
* `WPGeo_Distance_Query()` class.
* `wpgeo_distance` shortcode.

== Upgrade Notice ==

= 1.0 =
Just install it :)