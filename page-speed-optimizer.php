<?php
/**
 * @package Page Speed Optimizer
 */
/*
Plugin Name: Page Speed Optimizer
Plugin URI: http://wordpress.org/plugins/page-speed-optimizer
Description: Increase speed of you site by creating additional database indexes. No configuration needed, just install. 
Version: 2.5
Author: HenryMachari
License: MIT
*/

class bpsSpeedUp {
	function activate() {
		self::createIndex('posts', 'post_name, post_date, post_type, post_status');
		self::createIndex('postmeta', 'post_id, meta_key');
	}
	
	function deactivate() {
		self::dropIndex('posts');
		self::dropIndex('postmeta');
	}
	
	function createIndex($tableName, $fields) {
		global $wpdb;
		if(!self::indexExist($tableName)) {
			$wpdb->query("CREATE INDEX ".$wpdb->prefix.$tableName."_i ON ".$wpdb->prefix.$tableName."(".$fields.")");	
		}
	}
	
	function dropIndex($tableName) {
		global $wpdb;
		if(self::indexExist($tableName)) {
			$wpdb->query("DROP INDEX ".$wpdb->prefix.$tableName."_i ON ".$wpdb->prefix.$tableName);
		}
	}
	
	function indexExist($tableName) {
		global $wpdb;
		$indexName = $tableName."_i";
		$re = $wpdb->get_results("SHOW INDEX FROM ".$wpdb->prefix.$tableName." WHERE Key_name='".$wpdb->prefix.$indexName."'");
		if(count($re) > 0) {
			return true;
		}
		return false;
	}
	
}

register_activation_hook(__FILE__, array('bpsSpeedUp','activate'));
register_deactivation_hook(__FILE__, array('bpsSpeedUp', 'deactivate'));


