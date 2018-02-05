<?php
require "vendor/autoload.php";

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * Says "Hello World" to new users
 *
 * @when before_wp_load
 */
// $hello_world_command = function() {
// 	WP_CLI::success( "Hello world." );
// };
// WP_CLI::add_command( 'hello-world', $hello_world_command );


class CXL_Command {
    public function __invoke( $args ) {
        WP_CLI::success( $args[0] );
				console.log('here');
				$this->removeIntercomLeadDupes();
    }
		public function removeIntercomLeadDupes() {

				// grab all leads from Intercom
				try {
					$leads = $this->getIntercomLeads();
				} catch (Exception $e) {
						echo 'Unable to get leads from Intercom', "\n";
       			echo 'Caught exception: ',  $e->getMessage(), "\n";
 	 			}

				// identify what leads are the duplicates
				$duplicate_leads = $this->findDuplicateLeads($leads);

				// remove the duplicate leads from Intercom
				try {
						$this->removeDuplicateLeads($duplicate_leads);
				} catch (Exception $e) {
						echo 'Unable to remove duplicate leads', "\n";
       			echo 'Caught exception: ',  $e->getMessage(), "\n";
 	 			}

		}

		public function getIntercomLeads() {
				//$leads =  api call needed to get leads
				// return $leads
		}

		public function findDuplicateLeads() {

		}

		public function removeDuplicateLeads() {
			
		}

}

WP_CLI::add_command( 'cxl-intercom', 'CXL_Command' );
