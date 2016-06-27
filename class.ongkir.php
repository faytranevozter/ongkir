<?php
class Ongkir
{
	var $api_key 		= "your-api-key"; // api key rajaongkir
	var $origin 		= 105; // city id
	var $cache 			= TRUE; // caching data
	var $cacheTimeout 	= 60*60*24*7; // in seconds (1 week)

	function __construct()
	{
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
			// set expired cache
			if ( ! isset($_SESSION['_myOngkir']['expired'])) {
				$_SESSION['_myOngkir']['expired'] = time()+$this->cacheTimeout;
			} else {
				// time to clear cache
				if (time() > $_SESSION['_myOngkir']['expired']) {
					$this->clearCache();
				}
			}
		}
	}

	function get_city($id=FALSE)
	{
		$id=$id?"?id={$id}":"";

		// cache
		if($this->cache && $id == FALSE) {
			if (isset($_SESSION['_myOngkir']['city'])) {
				return $_SESSION['_myOngkir']['city'];
			}
		}

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://api.rajaongkir.com/starter/city{$id}",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"key: " . $this->api_key
				),
			));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return FALSE;
		} else {
			$results = json_decode($response, true);
			if (is_array($results) && isset($results['rajaongkir']) && isset($results['rajaongkir']['results'])) {
				// caching data
				if($this->cache && $id == FALSE) {
					// save data into session
					$_SESSION['_myOngkir']['city'] = $results['rajaongkir']['results'];
				}
				return $results['rajaongkir']['results'];
			} else {
				return FALSE;
			}
		}
	}	

	function costs($destination, $weight, $courier)
	{
		// cache
		if($this->cache) {
			if (isset($_SESSION['_myOngkir']['cost'][$this->origin][$destination][$weight][$courier])) {
				return $_SESSION['_myOngkir']['cost'][$this->origin][$destination][$weight][$courier];
			}
		}
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://api.rajaongkir.com/starter/cost",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "origin=" . $this->origin . "&destination={$destination}&weight={$weight}&courier={$courier}",
			CURLOPT_HTTPHEADER => array(
				"content-type: application/x-www-form-urlencoded",
				"key: " . $this->api_key
				),
			));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return FALSE;
		} else {
			$results = json_decode($response, true);
			if (is_array($results) && isset($results['rajaongkir']) && isset($results['rajaongkir']['results'])) {
				$out['package'] = array();
				foreach ($results["rajaongkir"]["results"][0]['costs'] as $x => $y) {
					$y['etd'] = $y['cost'][0]['etd'];
					$y['note'] = $y['cost'][0]['note'];
					$y['cost'] = $y['cost'][0]['value'];
					$out['package'][$x] = $y;
				}

				$out['destination'] = $results['rajaongkir']['destination_details'];
				$out['origin'] = $results['rajaongkir']['origin_details'];

				// caching data
				if($this->cache) {
					// save data into session
					$_SESSION['_myOngkir']['cost'][$this->origin][$destination][$weight][$courier] = $out;
				}
				return $out;
			} else {
				return FALSE;
			}

		}
	}

	function clearCache(){
		if (isset($_SESSION['_myOngkir'])) {
			unset($_SESSION['_myOngkir']);
		}
	}

}
