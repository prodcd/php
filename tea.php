<?php
/* TEA算法的PHP实现
 * 主要用来对uint32的id进行简单加密，以防止泄露数据量
 */


// 使用示例
$key = 0x0123456789abcdef; // 密码，固定长度

// 循环验证加密解密过程是否正常
for ($i=0;$i<1000;$i++){
    $data = $i;

    // 加密
    $encrypted_data = tea_encrypt($data, $key);
    echo "加密后的数据：" . $encrypted_data . "\n<br>";
    
    // 解密
    $decrypted_data = tea_decrypt($encrypted_data, $key);
    echo "解密后的数据：" . $decrypted_data . "\n<br>";

    if ($decrypted_data != $data) echo "错误：$i";
}

// 加密
function tea_encrypt($v, $k) {
    $delta = 0x9e3779b9;
    $sum = 0;

    // 循环次数为32加key的后4bit，范围在32~47之间
    $rounds = 32 + $k & 0xF;
    
    // 将64位密钥分解成4个16位子密钥
    $k0 = ($k >> 48) & 0xFFFF;
    $k1 = ($k >> 32) & 0xFFFF;
    $k2 = ($k >> 16) & 0xFFFF;
    $k3 = $k & 0xFFFF;

    for ($i = 0; $i < $rounds; $i++) {
        // 更新sum值
        $sum += $delta;
        
        // 将要加密的数据分成两个16位块
        $v1 = ($v >> 16) & 0xFFFF;
        $v0 = $v & 0xFFFF;

        // 进行TEA加密算法
        $v0 += (((($v1 << 4) & 0xFFFF) + $k0) ^ ($v1 + $sum) ^ ((($v1 >> 5) & 0xFFFF) + $k1));
        $v0 &= 0xFFFF;

        // 将加密后的结果组合成一个32位块
        $v1 += (((($v0 << 4) & 0xFFFF) + $k2) ^ ($v0 + $sum) ^ ((($v0 >> 5) & 0xFFFF) + $k3));
        $v1 &= 0xFFFF;
        $v = ($v1 << 16) | $v0;
    }

    return $v;
}

// 解密
function tea_decrypt($v, $k) {
    $delta = 0x9e3779b9;
    
    $rounds = 32 + $k & 0xF;
    $sum = $delta * $rounds;

    // 将64位密钥分解成4个16位子密钥
    $k0 = ($k >> 48) & 0xFFFF;
    $k1 = ($k >> 32) & 0xFFFF;
    $k2 = ($k >> 16) & 0xFFFF;
    $k3 = $k & 0xFFFF;

    for ($i = 0; $i < $rounds; $i++) {
        // 将密文分成两个16位块
        $v1 = ($v >> 16) & 0xFFFF;
        $v0 = $v & 0xFFFF;

        // 进行TEA解密算法
        $v1 -= (((($v0 << 4) & 0xFFFF) + $k2) ^ ($v0 + $sum) ^ ((($v0 >> 5) & 0xFFFF) + $k3));
        $v1 &= 0xFFFF;

        $v0 -= (((($v1 << 4) & 0xFFFF) + $k0) ^ ($v1 + $sum) ^ ((($v1 >> 5) & 0xFFFF) + $k1));
        $v0 &= 0xFFFF;

        // 将解密后的结果组合成一个32位块
        $v = ($v1 << 16) | $v0;
        
        // 更新sum值
        $sum -= $delta;
    }

    return $v;
}



?>
