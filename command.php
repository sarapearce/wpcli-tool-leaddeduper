<?php
/**
 * Plugin Name: CXL Intercom Lead DeDuper
 * Plugin URI:
 * Description: A spec project for CXL to build a WP CLI tool
 *             that cleans (dedupes) leads in a remote API for Intercomm
 * Version: 1.0.0
 * Author: Sara Pearce
 * Author URI: http://sarapearce.net
 * License: GPL2
 */

// load the intercom/php module
require "vendor/autoload.php";
use Intercom\IntercomClient;

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

if (! class_exists('CXL_Command') ) {

class CXL_Command {

    public function __invoke( $args ) {
        WP_CLI::success( $args[0] );
				// build the Intercom object
				$token = $this->getToken();
				try {
					$this->intercom = new IntercomClient($token, null);
				} catch (Exception $e) {
						echo 'Unable to load Intercom Object', "\n";
						echo 'Caught exception: ',  $e->getMessage(), "\n";
						die();
				}

				$this->removeLeadDupes();
    }

		public function removeLeadDupes() {

				// identify what leads are the duplicates, and get a list of their ids
				$duplicate_leads = $this->findDuplicateLeads($this->intercom->leads->getLeads([]));

				// remove the duplicate leads from Intercom
				// $success = $this->removeDuplicateLeads($duplicate_leads);

				// for testing
				echo 'Success: ' . $success;
		}

		public function getToken() {
			return get_option('intercom-token');
		}

		public function findDuplicateLeads($leads) {
			$unique_leads = [];
			$duplicate_leads = [];
			// var_dump($leads->contacts[0]->email);
			//  die();

			foreach($leads->contacts as $key => $lead) {

				// use a unique email as an indicator of a unique lead
				if (!in_array($leads->contacts[$key]->email, $unique_leads)) {
					array_push($unique_leads, $leads->contacts[$key]->email);
				} else {
					// the leads->contact object has extra metadata, get rid of that
					// $clean_id = substr($leads->contacts[$key]->id, -26);
					// print_r($clean_id);
					array_push($duplicate_leads, $leads->contacts[$key]->id);
				}
			}



			// confirm we havent left behind any dupes
			if (array_unique($unique_leads)) {
				return $duplicate_leads;
			} else {
				echo 'the unique leads array still has dupes';
			}
		}

		public function removeDuplicateLeads($duplicate_leads) {
			foreach ($duplicate_leads as $lead) {
				// example from docs
				// $intercom->leads->deleteLead("596f6c41a43a45f05de3275f");
				try {
					$this->intercom->leads->deleteLead($lead.id);
				} catch (Exception $e) {
						echo 'Unable to delete duplicate leads', "\n";
       			echo 'Caught exception: ',  $e->getMessage(), "\n";
						return false;
 	 			}
		}
		return true;
}
}


}
}
WP_CLI::add_command( 'cxl-intercom', 'CXL_Command' );
}
?>
