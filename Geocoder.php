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
  public $key = null;

  /*
   * Default endpoint for geocoding API calls
   */
  public $endpoint = 'https://maps.googleapis.com/maps/api/geocode';

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

  function __construct($key=self::API_KEY) {
    $this->key = $key;
    $this->curl = curl_init();
  }

  public function location($address, $options = array()) {
    $this->setOptions($options);
    $this->location = $this->request($address, $this->json_decode);
    $this->addressComponents();
    $this->formattedAddress();
    $this->coordinate();
    return $this->location;
  }

  /**
   * Gets a coordinate result from Google Maps API, sets the response to $response property
   * Sets lat/lng coordinate pair to $coordinate property
   * @param  string $address  The input for this method, address you want to get geocode data for
   * @param  string $options  An associative array of options. Keys and values are set as object properties.
   * @return array  $response JSON decoded response of geocoded data
   */
  public function coordinate($address = null, $options = array()) {
    $this->setOptions($options);
    if ($address) {
      $this->location = $this->location($address);
    }
    $this->coordinate = $this->location['results'][0]['geometry']['location'];
    return $this->coordinate;
  }

  public function addressComponents($address = null, $options = array()) {
    $this->setOptions($options);
    if (!in_array($this->length, array('long_name','short_name'))) {
      throw new Exception("The length parameter must be one of 'long_name','short_name'", 1);
    }

    if ($address) {
      $this->location = $this->location($address);
    }

    try {
      $acs = $this->location['results'][0]['address_components'];
      $this->address_components = array();
      foreach ($acs as $loc) {
        $this->address_components[$loc['types'][0]] = $loc[$this->length];
      }
      return $this->address_components;
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }

  public function formattedAddress($address = null, $options = array()) {
    if ($address) {
      $this->location = $this->location($address);
    }
    $this->formatted_address = $this->location['results'][0]['formatted_address'];
    return $this->formatted_address;
  }

/**
 * Accepts an array of options and sets the key of each as a property of this object
 * and the value of each to its key.
 * @param array $options
 * @return void
 */
  private function setOptions($options = array()) {
    $defaults = array(
      'json_decode' => true,
      'length' => 'short_name'
    );
    $options = array_merge($defaults, $options);
    if (!empty($options)) {
      foreach ($options as $k => $v) {
        $this->{$k} = $v;
      }
    }
    return;
  }

  private function request($address, $json_decode = false) {
    $request = $this->endpoint.'/'.$this->format.'?address='.$address.'&key='.$this->key;
    curl_setopt($this->curl, CURLOPT_URL, $request);
    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($this->curl);
    $response = (!$json_decode) ? $response : json_decode($response, true);
    if (curl_errno($this->curl)) {
      return curl_error($this->curl);
    } else {
      return $response;
    }

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