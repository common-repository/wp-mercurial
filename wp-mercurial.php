<?php
/*
Plugin Name: WP Mercurial
Plugin URI: https://charles.lecklider.org/wordpress/wp-mercurial
Description: Basic Mercurial funtionality from the dashboard. Automatically commit after updating core, plugins, or themes.
Version: 1.1
Author: Charles Lecklider
Author URI: https://charles.lecklider.org/
License: GPL2
*/

/*  Copyright 2012  Charles Lecklider  (email : wordpress@charles.lecklider.org)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if (is_admin()) {

	add_action( '_core_updated_successfully',
				function ($wp_version)
				{
					echo '<pre>';
					system("hg commit -A -m 'Updated WordPress to $wp_version'");
					echo '</pre>';
				});

	add_action( 'wp_dashboard_setup', 'wp_mercurial_dashboard_widget' );
	add_action( 'wp_network_dashboard_setup', 'wp_mercurial_dashboard_widget' );

	add_filter( 'upgrader_post_install',
				function ($install_result, $hook_extra, $child_result)
				{
					if (strstr($child_result['destination'],'/plugins/')) {
						$what = 'plugin';
					} elseif (strstr($child_result['destination'],'/themes/')) {
						$what = 'theme';
					} else {
						return $install_result;
					}
					$action = ($child_result['clear_destination'])
								? 'Updated'
								: 'Installed';

					echo '<pre>';
					system("hg commit -A -m '$action $what: {$child_result['destination_name']}'");
					echo '</pre>';

					return $install_result;
				},
				10,
				3);

	function wp_mercurial_dashboard_widget()
	{
		if (current_user_can('manage_options')) {
			wp_add_dashboard_widget('wp_mercurial_dashboard_widget',
									'WP Mercurial',
									'wp_mercurial_dashboard_widget_function');
			wp_enqueue_style('wp-mercurial',plugins_url('wp-mercurial.css',__FILE__));
		}
	}

	function wp_mercurial_dashboard_widget_function()
	{
?>
		<form id="wp-mercurial" method="post">
			<?php wp_nonce_field('wp-mercurial') ?>
			<input type="hidden" name="wp-mercurial" value="widget">
			<span><input type="submit" name="action" class="button-primary" value="Commit"></span>
			<h4><label for="commit">Commit</label></h4>
			<div class="input-text-wrap"><input id="commit" type="text" name="commit"></div>
			<p>
				<input type="submit" name="action" class="button-primary" value="Push">
				<input type="submit" name="action" class="button" value="Status">
				<input type="submit" name="action" class="button" value="Log">
				<input type="submit" name="action" class="button" value="Add/Remove">
				<input type="submit" name="action" class="button" value="Pull">
				<input type="submit" name="action" class="button" value="Update">
				<input type="submit" name="action" class="button" value="Merge">
				<input type="submit" name="action" class="button" value="Verify">
			</p>
		</form>
<?php	if ('widget' == @$_POST['wp-mercurial'] && wp_verify_nonce(@$_POST['_wpnonce'],'wp-mercurial')) { ?>
		<hr>
		<pre>
<?php
			switch(@$_POST['action']) {
			case 'Push':
				echo "hg push\n";
				system('hg push');
				break;
			case 'Status':
				echo "hg status:\n";
				system('hg status');
				break;
			case 'Log':
				echo "hg log:\n";
				system('hg log');
				break;
			case 'Add/Remove':
				system('hg addremove');
				break;
			case 'Commit':
				if (strlen(@$_POST['commit'])) {
					$msg = escapeshellarg($_POST['commit']);
					echo "hg commit:\n";
					system("hg commit -v -m '$msg'");
				}
				break;
			case 'Pull':
				echo "hg pull:\n";
				system('hg pull');
				break;
			case 'Update':
				echo "hg update:\n";
				system('hg update');
				break;
			case 'Merge':
				echo "hg merge\n";
				system('hg merge');
				break;
			case 'Verify':
				echo "hg verify\n";
				system('hg verify');
				break;
			}
?>
		</pre>
<?php
		}
	}
}

