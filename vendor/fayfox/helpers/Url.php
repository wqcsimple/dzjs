<?php
namespace fayfox\helpers;

class Url{
	
	/**
	 * combineURL
	 * 拼接url
	 * @param string $baseURL 基于的url
	 * @param array $keysArr 参数列表数组
	 * @return string 返回拼接的url
	 */
	public static function combineURL($baseURL,$keysArr){
		$combined = $baseURL."?";
		$valueArr = array();
	
		foreach($keysArr as $key => $val){
			$valueArr[] = "$key=$val";
		}
	
		$keyStr = implode("&",$valueArr);
		$combined .= ($keyStr);
	
		return $combined;
	}
	
	/**
	 * get_contents
	 * 服务器通过get请求获得内容
	 * @param string $url 请求的url,拼接后的
	 * @return string 请求返回的内容
	 */
	public static function get_contents($url){
		if (ini_get("allow_url_fopen") == "1") {
			$response = file_get_contents($url);
		}else{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_URL, $url);
			$response =  curl_exec($ch);
			curl_close($ch);
		}
	
		return $response;
	}
	
	/**
	 * get
	 * get方式请求资源
	 * @param string $url 基于的baseUrl
	 * @param array $keysArr 参数列表数组
	 * @return string 返回的资源内容
	 */
	public static function get($url, $keysArr){
		$combined = self::combineURL($url, $keysArr);
		return self::get_contents($combined);
	}
	
	/**
	 * post
	 * post方式请求资源
	 * 从淘宝的sdk里抄的
	 */
	public static function post($url, $postFields = null){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//https 请求
		if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}
		
		if (is_array($postFields) && 0 < count($postFields)){
			$postBodyString = "";
			$postMultipart = false;
			foreach ($postFields as $k => $v){
				if("@" != substr($v, 0, 1)){//判断是不是文件上传
					$postBodyString .= "$k=" . urlencode($v) . "&";
				}else{//文件上传用multipart/form-data，否则用www-form-urlencoded
					$postMultipart = true;
				}
			}
			unset($k, $v);
			curl_setopt($ch, CURLOPT_POST, true);
			if ($postMultipart){
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			}else{
				curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
			}
		}
		$reponse = curl_exec($ch);
		
		if (curl_errno($ch)){
			return false;
			//throw new Exception(curl_error($ch),0);
		}else{
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode){
				return $httpStatusCode;
				//throw new Exception($reponse,$httpStatusCode);
			}
		}
		curl_close($ch);
		return $reponse;
	}
}