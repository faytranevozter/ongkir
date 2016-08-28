# Library Ongkir Class
Simple Library PHP to get costs from courier (JNE,TIKI,POS) with RajaOngkir 

## Why?
- Easy to use
- Save data into session
- Tiny file size
- Absolutely **_FREE_**

## Demo
[See The Demo](http://www.elfay.id/ongkir "Demo")

## Configuration

```php
public $api_key 		= "xxxxxxxxxxxxxxxxxxx"; // your api key rajaongkir
public $origin  		= 105; // city id (read how to get city_id down below)
public $cache   		= TRUE; // caching data (TRUE | FALSE)
public $cacheTimeout	= 60*60*24*7; // timeout cache in seconds (1 week example)
```

## Using the class

**Include and use the ongkir class**

```php
include 'class.ongkir.php';

$ongkir = new Ongkir();
```

**Get all province**
```php
$ongkir->get_province();
```
Output:
```php
// Array
// (
// 	[1] => Array
// 	(
// 		[province_id] => 1
// 		[province] => Bali
// 	),
//  [2] => Array
//  (
//      [province_id] => 2
//      [province] => Bangka Belitung
//  ),
// 	...
// )
```


**Get province by id**
```php
$ongkir->get_province(5);
```
Output:
```php
// Array
// (
// 		[province_id] => 5
// 		[province] => DI Yogyakarta
// )
```


**Get all city**
```php
$ongkir->get_city();
```
Output: 
```php
// Array
// (
//  [1] => Array
//  (
//  	[city_id] => 1
//  	[province_id] => 21
//  	[province] => Nanggroe Aceh Darussalam (NAD)
//  	[type] => Kabupaten
//  	[city_name] => Aceh Barat
//  	[postal_code] => 23681
//  ), 
//  [2] => Array
//  (
//  	[city_id] => 2
//  	[province_id] => 21
//  	[province] => Nanggroe Aceh Darussalam (NAD)
//  	[type] => Kabupaten
//  	[city_name] => Aceh Barat Daya
//  	[postal_code] => 23764
//  ),
// 	...
// )
```


**Get city by id**
```php
$ongkir->get_city(501);
```
Output:
```php
// Array
// (
//  	[city_id] => 501
//  	[province_id] => 5
//  	[province] => DI Yogyakarta
//  	[type] => Kota
//  	[city_name] => Yogyakarta
//  	[postal_code] => 55222
// )
```



**Get costs package**
```php
$destination = 501; // city_id
$weight = 1700; // weight in gram
$courier = 'jne' // jne | tiki | pos
$ongkir->costs($destination, $weight, $courier);
```
Output:
```php
// Array
// (
//  [package] => Array
//   (
//     [0] => Array
//         (
//             [service] => OKE
//             [description] => Ongkos Kirim Ekonomis
//             [cost] => 15000
//             [etd] => 2-3
//             [note] => 
//         ),
//     [1] => Array
//         (
//             [service] => REG
//             [description] => Layanan Reguler
//             [cost] => 17000
//             [etd] => 1-2
//             [note] => 
//         ),
// 	...
//   ),
// 
//  [destination] => Array
//   (
//     [city_id] => 501
//     [province_id] => 5
//     [province] => DI Yogyakarta
//     [type] => Kota
//     [city_name] => Yogyakarta
//     [postal_code] => 55222
//   ),
// 
//  [origin] => Array
//   (
//     [city_id] => 105
//     [province_id] => 10
//     [province] => Jawa Tengah
//     [type] => Kabupaten
//     [city_name] => Cilacap
//     [postal_code] => 53211
//   )
// 
// )
```

## Get the city_id
To get the city_id just test the ```get_city()``` method
or see [Province and City ID](https://github.com/faytranevozter/ongkir/blob/master/province.city.txt "Province and City ID")
#### NOTE: VIEWING DOCUMENT IS NOT RECOMMENDED, TEST THE ```get_city``` METHOD AS POSSIBLE
