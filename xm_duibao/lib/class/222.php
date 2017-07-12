<?php



/* $squares = returnSquarePoint($lng,$lat);


$bb = $squares['right-bottom']['lat'];
$dd = $squares['left-top']['lat'];
$ff = $squares['left-top']['lng'];






print_r($r);
 */

$lat = '39.864591193638';
$lng = '116.43268516415';


/* $lat2 = '39.926183349525';
$lng2 = '116.43267610971'; */
$lat2 = '39.886693906557';
$lng2 = '116.4046743417';

$dd = weizhi($lat,$lng,$lat2,$lng2);

$ff = distance($lat,$lng,$lat2,$lng2);

$bb = getDistance($lat,$lng,$lat2,$lng2);

print_r($dd);echo '--';
print_r($ff);echo '--';
print_r($bb); echo '--';


function weizhi($lng1,$lat1,$lng2,$lat2){
	//将角度转为狐度
	$radLat1=deg2rad($lat1);//deg2rad()函数将角度转换为弧度
	$radLat2=deg2rad($lat2);
	$radLng1=deg2rad($lng1);
	$radLng2=deg2rad($lng2);
	$a=$radLat1-$radLat2;
	$b=$radLng1-$radLng2;
	$s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137*1000;
	return $s;
}

function distance($lat1, $lng1, $lat2, $lng2, $miles = true){ 
	$pi80 = M_PI / 180; 
	$lat1 *= $pi80; 
	$lng1 *= $pi80; 
	$lat2 *= $pi80; 
	$lng2 *= $pi80; 
	$r = 6371; // mean radius of Earth in km 
	$dlat = $lat2 - $lat1; 
	$dlng = $lng2 - $lng1; 
	$a = sin($dlat/2)*sin($dlat/2)+cos($lat1)*cos($lat2)*sin($dlng/2)*sin($dlng/2); 
	$c = 2 * atan2(sqrt($a), sqrt(1 - $a)); 
	$km = $r * $c; 
	return ($miles ? ($km * 0.621371192) : $km);
}



/**
* 计算两组经纬度坐标 之间的距离
* params ：lat1 纬度1； lng1 经度1； lat2 纬度2； lng2 经度2； len_type （1:m or 2:km);
* return m or km
*/
function getDistance($lat1, $lng1, $lat2, $lng2, $len_type = 2, $decimal = 2){
$EARTH_RADIUS=6378.137;//6371
$EARTH_RADIUS=6371;
	$PI=3.1415926;
	$radLat1 = $lat1 * $PI / 180.0;
	$radLat2 = $lat2 * $PI / 180.0;
	$a = $radLat1 - $radLat2;
	$b = ($lng1 * $PI / 180.0) - ($lng2 * $PI / 180.0);
	$s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
	$s = $s * $EARTH_RADIUS;
	$s = round($s * 1000);
	if ($len_type > 1)
	{
	$s /= 1000;
}
return round($s,$decimal);
}



















function returnSquarePoint($lng, $lat,$distance = 300){

	$dlng =  2 * asin(sin($distance / (2 *6371)) / cos(deg2rad($lat)));
	$dlng = rad2deg($dlng);

	$dlat = $distance/6371;
	$dlat = rad2deg($dlat);


	return array(
			'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
			'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
			'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
			'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
	);
}


