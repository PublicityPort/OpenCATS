 <?php
/**
 * CATS
 * Zip Code Lookup Library
 *
 * Copyright (C) 2005 - 2007 Cognizo Technologies, Inc.
 *
 *
 * The contents of this file are subject to the CATS Public License
 * Version 1.1a (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.catsone.com/.
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is "CATS Standard Edition".
 *
 * The Initial Developer of the Original Code is Cognizo Technologies, Inc.
 * Portions created by the Initial Developer are Copyright (C) 2005 - 2007
 * (or from the year in which this file was created to the year 2007) by
 * Cognizo Technologies, Inc. All Rights Reserved.
 *
 *
 * @package    CATS
 * @subpackage Library
 * @copyright Copyright (C) 2005 - 2007 Cognizo Technologies, Inc.
 * @version    $Id: ZipLookup.php 3587 2007-11-13 03:55:57Z will $
 */
/**
 *	Zip Code Lookup Library
 *	@package    CATS
 *	@subpackage Library
 */
class ZipLookup
{
    /**
     * Makes a proper "searchable" United States Zip code from a given Zip
     * code string. All whitespace and leading 0's are removed.
     *
     * @param string free-form Zip code (ex: 55121, 30504 - 1000, 01369-5463)
     * @return integer 3-5 digit searchable zip code or 0 on failure
     */
     public static function makeSearchableUSZip($zipString)
     {
        if (preg_match('/^\s*[0]*(\d{3,5})\s*(?:-.*)?$/', $zipString, $match))
        {
            return (int) $match[1];
        }
        return 0;
     }
    /**
     * Finds City and State names via United States Zip code. The Zip code
     * should be specified as an integer with no leading 0s.
     *
     * @param integer United States Zip code
     * @return array city / state data (empty strings if not found)
     */
    public function getCityStateByZip($zip)
    {
        /* Make sure we have an integer. */
        $zip = (int) $zip;
        if ($zip === 0)
        {
            return array('city' => '', 'state' => '');
        }
        $db = DatabaseConnection::getInstance();
        $sql = sprintf(
            "SELECT
                city AS city,
                state AS state
            FROM
                zipcodes
            WHERE
                zipcode = %s",
            $zip
        );
        $data = $db->getAssoc($sql);
        if (empty($data))
        {
            return array('city' => '', 'state' => '');
        }
        return $data;
    }
    
    /**
     * Returns an array of SQL clauses that returns the distance from a zipcode for each record.
     *
     * @param integer United States Zip code (55303)
     * @param string record Zip Code Column (candidate.zip)
     * @return string SQL select clause
     */
    public function getDistanceFromPointQuery($zipcode, $zipcodeColumn)
    {
        //based on kilometers = (3958*3.1415926*sqrt(($lat2-$lat1)*($lat2-$lat1) + cos($lat2/57.29578)*cos($lat1/57.29578)*($lon2-$lon1)*($lon2-$lon1))/180);
        
        $select = "(3958*3.1415926*sqrt((zipcode_searching.lat-zipcode_record.lat)*(zipcode_searching.lat-zipcode_record.lat) + cos(zipcode_searching.lat/57.29578)*cos(zipcode_record.lat/57.29578)*(zipcode_searching.lng-zipcode_record.lng)*(zipcode_searching.lng-zipcode_record.lng))/180) as distance_km";
        $join = "LEFT JOIN zipcodes as zipcode_searching ON zipcode_searching.zipcode = ".$zipcode." LEFT JOIN zipcodes as zipcode_record ON zipcode_record.zipcode = ".$zipcodeColumn;
        return array("select" => $select, "join" => $join);
    }

	// #############################################################
	// #############################################################

	public function getDistance($fLat1, $fLng1, $fLat2, $fLng2) {
		$iEarthRadius = 6371;

		$fLatRad = deg2rad($fLat2 - $fLat1);
		$fLonRad = deg2rad($fLng2 - $fLng1);
		$a = sin($fLatRad/2) * sin($fLatRad/2) + cos(deg2rad($fLat1)) * cos(deg2rad($fLat2)) * sin($fLonRad/2) * sin($fLonRad/2);
		$c = 2 * asin(sqrt($a));
		$d = $iEarthRadius * $c;

        	return $d;
	}

	public function getDistanceFromAddress($sAddress1 = '', $sAddress2 = '') {
		$ret = -1;

		$sUrl = 'http://maps.googleapis.com/maps/api/geocode/xml?sensor=false&address=';

		if ($sAddress1 != '' && $sAddress2 != '') {
			if (($oXml = simplexml_load_file($sUrl . $sAddress1))) {
				$fLat1 = floatval($oXml->result->geometry->location->lat);
				$fLng1 = floatval($oXml->result->geometry->location->lng);
				if (($oXml = simplexml_load_file($sUrl . $sAddress2))) {
					$fLat2 = floatval($oXml->result->geometry->location->lat);
					$fLng2 = floatval($oXml->result->geometry->location->lng);
					$ret = $this->getDistance($fLat1, $fLng1, $fLat2, $fLng2) * 0.621371192;
				} else {
					$ret = -2;
				}
			} else {
				$ret = -2;
			}
		}

		return $ret;
	}

}

?>