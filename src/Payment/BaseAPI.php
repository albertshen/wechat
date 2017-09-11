<?php

namespace Wechat\Payment;

use Wechat\Payment\Exception\InvalidArgumentException;

class BaseAPI 
{

    public function request($api, $params)
    {
        $params['nonce_str'] = $this->getNonceStr();
        $params['fee_type'] = $this->merchant->fee_type;
        $params['sign'] = $this->generateSign($params, $this->merchant->key);
        return $this->postXmlCurl($this->ToXml($params), $api);
    }

    /**
     * 
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return 产生的随机字符串
     */
    public function getNonceStr($length = 32) 
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
        $str = "";
        for ( $i = 0; $i < $length; $i++ )  {  
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
        } 
        return $str;
    }

    function generateSign(array $attributes, $key, $encryptMethod = 'md5')
    {
        ksort($attributes);

        $attributes['key'] = $key;

        return strtoupper(call_user_func_array($encryptMethod, [urldecode(http_build_query($attributes))]));
    }

    /**
     * 输出xml字符
     * @throws WxPayException
    **/
    public function ToXml($values)
    {
        if(!is_array($values) 
            || count($values) <= 0)
        {
            throw new InvalidArgumentException("数组数据异常！");
        }
        
        $xml = "<xml>";
        foreach ($values as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml; 
    }
    
    /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
    public function FromXml($xml)
    {   
        if(!$xml){
            throw new InvalidArgumentException("xml数据异常！");
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
        return $values;
    }

    /**
     * 以post方式提交xml到对应的接口url
     * 
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    public function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {    print ($xml);exit;
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    
        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch, CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, $this->marchant->ssl_cert_path);
            curl_setopt($ch, CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, $this->marchant->ssl_key_path);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            throw new Exception("curl出错，错误码:$error");
        }
    }
}
