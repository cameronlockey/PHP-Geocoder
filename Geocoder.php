 <?php

/*
A PHP wrapper for the Google Maps API geocoding services.
Requires json_decode be available.
Licensed under the GNU General Public License.
Cameron Lockey, cameron@localeyesite.com
*/

class Geocoder {

  // Use your google maps API key here, or provide it as a parameter on each call.
  const API_KEY = null;

  /*
   * Set your api key to this ivar property
   * -- this keeps this class flexible
   */
  public $apiKey = null;

  /*
   * Default endpoint for geocoding API calls
   */
  public $host = 'https://maps.googleapis.com/maps/api/geocode';

  /*
   * Default return format
   */
  public $format = 'json';

  /*
   * Geocoding API response in array format
   */
  public $response = array();

  /*
   * Coordinate lat/lng pair, null by default
   */
  public $coordinate = array(
    'lat' => null,
    'lng' => null
  );

  /**
   * Gets a geocoding result from Google Maps API, sets the response to $response property
   * Sets lat/lng coordinate pair to $coordinate property
   * @param  string $address  The input for this method, address you want to get geocode data for
   * @param  string $key      Override for api key if not set to instance
   * @return array  $response JSON decoded response of geocoded data
   */
  public function geocode($address, $key=self::API_KEY) {
    if ($this->apiKey) {
      $key = $this->apiKey;
    }

    if ($this->host) {
      $host = $this->host;
    }

    if (!$key) {
      throw new Exception("Add your Google Maps API key to the source to use this function without passing it as a parameter, or else set it to the apiKey property of your Geocode instance.");
    }

    $address = urlencode($address);
    $request = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&key=".$key;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $request);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = json_decode(curl_exec($ch), true);

    $this->response = $response;
    $this->coordinate = $this->response['results'][0]['geometry']['location'];

    return $response;
  }

}

/*

PHP-Geocoder
A super-basic PHP wrapper class for the Google Maps API 'geocode' endpoint.

Copyright (c) 2015 Cameron Lockey

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