<?php


/**
 * Example calls to get full location and coordinate for zipcode 27604
 */

// Require Geocoder class to make it available for instantiation
require_once('Geocoder.php');

// Create an instance of Geocoder, including our Google Maps API key
$geo = new Geocoder('AIzaSyBNnzeui8ZTUSGaWapW-cms58yHOaXhnNk');

// Initialize the location
$geo->location('27604');

// Get all the location details as an array
echo '<pre>'; echo print_r($geo->location); echo '</pre>';

// Get the formatted address
echo '<pre>'; echo $geo->formatted_address; echo '</pre>';

// Get the short address components (short_name by default)
echo '<pre>'; echo print_r($geo->address_components); echo '</pre>';

// Get the short address components (long_name)
echo '<pre>'; echo print_r($geo->addressComponents(null, array('length' => 'long_name'))); echo '</pre>';

// Get the coordinate only
echo '<pre>'; echo print_r($geo->coordinate); echo '</pre>';

