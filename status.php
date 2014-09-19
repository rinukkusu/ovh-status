<?php
/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
	
	/**
	 * Add the servers you want to watch here
	 * eg.: $match_servers = array("142sys6", "141bk2");
	 */
	$match_servers = array();
	
//====================================================================

	$bad_availability = array("unknown", "unavailable");
	
	$avail_json = file_get_contents("http://www.soyoustart.com/fr/js/dedicatedAvailability/availability-data.json");

	if (count($match_servers) == 0) {
		echo "Error: no servers to watch specified.";
	}
	
	if ($avail_json !== false) {
		$availability = json_decode($avail_json);

		if ($availability !== null) {
			foreach($availability->availability as $server) {
				if (in_array($server->reference, $match_servers)) {
					$output = "";
					$available = false;
					foreach($server->zones as $zone) {
						if (!in_array($zone->availability, $bad_availability)) {
							$output .= "  - " . $zone->zone . ": " . $zone->availability . "\r\n";
							$available = true;
						}
					}
					
					if ($available) {
						$output = $server->reference . ": \r\n" . $output . "\r\n";
						echo $output;
					}
				}
			}
		}
		else {
			echo "Error: data not in a valid JSON format.";
		}
	}
	else {
		echo "Error: couldn't retrieve availability data from server.";
	}
?>
