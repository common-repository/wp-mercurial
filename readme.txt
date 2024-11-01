=== WP Mercurial ===
Contributors: invisnet
Author URI: https://charles.lecklider.org/
Plugin URI: https://charles.lecklider.org/wordpress/wp-mercurial/
Tags: mercurial
Requires at least: 3.4.0
Tested up to: 3.4.2
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Basic Mercurial functionality from the dashboard. Automatically commit after updating core, plugins, or themes.

== Description ==

Not everyone has the luxury of seperate development, staging, and live servers. *WP Mercurial* helps work around the limitations of a single server by automating many of the repetitive Mercurial tasks required when updating WordPress.

Each time a plugin, a theme, or the core is updated, *WP Mercurial* will automatically run:

		hg -A commit -m '<description of update>'

The description is based on what was updated.

*WP Mercurial* never pushes automatically.

There is also a dashboard widget that provides all the basic Hg commands.

== Installation ==

1. Upload the plugin to your plugins directory
1. Activate the plugin through the 'Plugins' menu in WordPress

There are no WordPress options to configure.

== Changelog ==

= 1.1 =
Minor cosmetic updates.

= 1.0 =
Initial release.

