<?php
/*
 * 对于自定义jwt内容的一些尝试和考虑
 * 感觉还是不如sessionid好用，仅仅是减少了一些查询
 * 如果需要查询的内容很多，确实可以降低一些负载
 * 但降低了控制能力，过期前jwt一直有效
 */
$jwt['key'] = 'R19CPmrS6wPLicKbFIlZ'; // 密钥只存储在服务器端
$jwt['p'] = 'mine'; // 项目名称
$jwt['salt'] = 'PmrS6wPL'; // 加盐，增加破解难度
$jwt['v'] = '1'; // 携带版本信息，方便升级格式
$jwt['u'] = 10; // 用户id
$jwt['exp'] = '23-03-05 12:34:56';  // 可以考虑更易读的时间格式
$jwt['ip'] = '123.456.789.12'; // 考虑可以限制jwt的使用IP
// 签名取md5前16位
$jwt['sig'] = substr(md5($jwt['p'] . $jwt['v'] . $jwt['u'] . $jwt['exp']), 0, 16); // 签发jwt时，带key生成sig，签名信息只取16位即可。
unset($jwt['key']); // 颁发给用户的jwt不能带key
echo json_encode($jwt);
// {"p":"mine","salt":"PmrS6wPL","v":"1","u":10,"exp":"23-03-05 12:34:56","ip":"123.456.789.12","sig":"2032af9aa3973129"}