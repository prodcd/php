<?php
/**
 * 计算两个经纬度点之间的距离
 * 貌似两个函数都可用，有极小误差
 */

function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    // 地球半径（单位：米）
    $radius = 6371000;

    // 将经纬度转换为弧度
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    // 使用Haversine公式计算两个点之间的距离
    $deltaLat = $lat2 - $lat1;
    $deltaLon = $lon2 - $lon1;
    $a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos($lat1) * cos($lat2) * sin($deltaLon / 2) * sin($deltaLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $radius * $c;

    return $distance;
}

$lat1 = 39.9042; // 第一个点的纬度
$lon1 = 116.4074; // 第一个点的经度
$lat2 = 39.9042; // 第二个点的纬度
$lon2 = 116.4075; // 第二个点的经度

$distance = calculateDistance($lat1, $lon1, $lat2, $lon2);
echo "实际距离：{$distance} 米";


function distance($lat1, $lon1, $lat2, $lon2) {
    $R = 6371000; // 地球半径，单位：千米
    $dLat = deg2rad($lat2 - $lat1);  // deg2rad见下方
    $dLon = deg2rad($lon2 - $lon1);
    $a = 
        sin($dLat/2) * sin($dLat/2) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2)
    ;
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $d = $R * $c; // Distance in meters
    return $d;
}

$d = distance($lat1, $lon1, $lat2, $lon2);
var_dump($d);