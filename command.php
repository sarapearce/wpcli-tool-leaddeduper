<?php
/**
 * Plugin Name: CXL Intercom Lead DeDuper
 * Plugin URI:
 * Description: A spec project for CXL to build a WP CLI tool
 * that cleans ( dedupes ) leads in a remote API for Intercomm
 * Version: 1.0.0
 * Author: Sara Pearce
 * Author URI: http://sarapearce.net
 * License: GPL2

 * @package Remote Database Cleanup
 */

/**
 * Load the intercom/php module
 */

require 'vendor/autoload.php';
use Intercom\IntercomClient;

if ( ! class_exists( ' WP_CLI ' ) ) {
	return;
}

if ( ! class_exists( 'CXL_Command_2' ) ) {
	/**
	 * This class builds an Intercom object that allows us to CRUD on the Intercom server,
	 * then it removes duplicate leads on the Intercom remote server.
	 */
	class CXL_Command {
		/**
		 * Get the token and build the Intercom object
		 * $args is an array of arguments, currently empty
		 */
		public function __invoke( $args ) {
			WP_CLI::success( $args[0] );

			/**
			 * Build the Intercom object
			 */

			$token = $this->get_token();
			try {
				$this->intercom = new IntercomClient( $token, null );

			} catch ( Exception $e ) {
				echo 'Unable to create Intercom Object ',  '\n';
				echo ' Caught exception:  ' ,  esc_url( $e->getMessage() ), ' \n ';
				die();
			}

			// $this->remove_lead_dupes();

				 $this->resetTestData();

		}

			/**
			 * Delete the duplicate leads using the Intercom object
			 */
		public function remove_lead_dupes() {
			/**
			 * Identify what leads are the duplicates, and get a list of their ids
			 */
			$duplicate_leads = $this->find_duplicate_leads( $this->intercom->leads->getLeads( [] ) );

			/**
			 * Remove the duplicate leads from Intercom
			 */
			$success = $this->remove_duplicate_leads( $duplicate_leads );
		}

			/**
			 * Get the Intercom token from the database
			 */
		private function get_token() {
			return get_option( ' intercom-token ' );
		}

			/**
			 * Used for testing. It generates 2 users with the email of sarapearce3.14@gmail.com
			 */
		private function reset_test_data() {
			/**
			 * Create the same user twice to create a duplicate
			 */
			$this->intercom->leads->create( [ ' email ' => ' sarapearce3.14@gmail.com ' ] );
			$this->intercom->leads->create( [ ' email ' => ' sarapearce3.14@gmail.com ' ] );

			echo 'Data Reset!';
		}
			/**
			 * Sort and isolate the duplicate leads using the email as the unique identifier.
			 *
			 * $leads is an object with contacts and metadata
			 */
		public function find_duplicate_leads( $leads ) {
			$unique_leads = [];
			$duplicate_leads = [];

			foreach ( $leads->contacts as $key => $lead ) {
				/**
				 * Use an email as an indicator of a unique lead
				 */
				if ( ! in_array( $leads->contacts[ $key ]->email, $unique_leads, true ) ) {
					array_push( $unique_leads, $leads->contacts[ $key ]->email );
				} else {
					array_push( $duplicate_leads, $leads->contacts[ $key ]->id );
				}
			}

			/**
			 * Confirm we havent left behind any dupes
			 */
			if ( array_unique( $unique_leads ) ) {
				return $duplicate_leads;
			} else {
				echo ' the unique leads array still has dupes ';
			}
		}

		/**
		 * Delete the duplicate leads using the Intercom object
		 *
		 * $duplicate_leads is an array of ids where each is the id for the lead that is a duplicate
		 */
		public function remove_duplicate_leads( $duplicate_leads ) {
			if ( empty( $duplicate_leads ) ) {
				return false;
			}
			foreach ( $duplicate_leads as $key => $lead ) {
				try {
					$this->intercom->leads->deleteLead( $lead );
				} catch ( Exception $e ) {
					echo ' Unable to delete duplicate leads ' ,  ' \n ';
					echo ' Caught exception:  ' ,  esc_url( $e->getMessage() ),'\n';
					return false;
				}
			}
			return true;
		}
	}

	/**
		* Add the function to the command object
		*/
	WP_CLI::add_command( 'cxl-intercom', 'CXL_Command_2' );
}
