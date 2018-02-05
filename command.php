<?php

// load the intercom/php module
require "vendor/autoload.php";

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

class CXL_Command {



    public function __invoke( $args ) {
        WP_CLI::success( $args[0] );
				console.log('here');
				// build the Intercom object
				$token = getToken();
				$this.intercom = new IntercomClient($token, '');
				$this->removeLeadDupes();
    }


		public function removeLeadDupes() {

				// grab all leads from Intercom
				$leads = $this->getLeads();

				// identify what leads are the duplicates, and get a list of their ids
				$duplicate_leads = $this->findDuplicateLeads($leads);

				// remove the duplicate leads from Intercom
				$success = $this->removeDuplicateLeads($duplicate_leads);

				// tell us if it worked
				echo 'Success: ' . $success;
		}

		public function getLeads() {
			try {
				$leads = $this.intercom->segments->getSegments(['leads']);
			} catch (Exception $e) {
					echo 'Unable to get leads from Intercom', "\n";
					echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
				return $leads;
		}

		public function getToken() {
			//figure out how to store and retrieve tokens from WPCLI cache/mem/whatev
		}

		public function findDuplicateLeads($leads) {
			$unique_leads = [];
			$duplicate_leads = [];

			foreach($leads as $lead) {
				// use a unique email as an indicator of a unique lead
				if (!in_array($lead.email, $unique_leads)) {
					array_push($unique_leads, $lead.email);
				} else {
					array_push($duplicate_leads, $lead.id);
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
				// ex from docs
				// $intercom->leads->deleteLead("596f6c41a43a45f05de3275f");
				try {
					$this.intercom->leads->deleteLead($lead.id);
				} catch (Exception $e) {
						echo 'Unable to delete duplicate leads', "\n";
       			echo 'Caught exception: ',  $e->getMessage(), "\n";
						return false;
 	 			}

		}
		return true;

}

WP_CLI::add_command( 'cxl-intercom', 'CXL_Command' );
