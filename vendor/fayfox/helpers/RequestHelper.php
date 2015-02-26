<?php
namespace fayfox\helpers;

class RequestHelper{
	public static function getIP(){
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$arr = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
			foreach($arr as $a){
				if(substr($a, 0, 2) == '10'){
					continue;
				}else if(substr($a, 0, 3) == '192'){
					continue;
				}else if(substr($a, 0, 3) == '172' && substr($a, 4, 2) >= 16 && substr($a, 4, 2) <= 31){
					continue;
				}else{
					return trim($a);
				}
			}
		}
		return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'cli';
	}
	
	/**
	 * 将ip转换为int存储，兼容32位和64位机器
	 * @param ip $ip
	 * @return int
	 */
	public static function ip2int($ip){
		if(!$r = ip2long($ip)) return 0;
		if($r > 2147483647)
			$r -= 4294967296;
		return $r;
	}
	
	public static function getBrowser($user_agent = null){
		$user_agent === null && $user_agent = $_SERVER['HTTP_USER_AGENT'];
		$browsers = 'mozilla msie gecko firefox ';
		$browsers.= 'konqueror safari netscape navigator ';
		$browsers.= 'opera mosaic lynx amaya omniweb';
		$browsers = explode(' ', $browsers);
	
		$nua = strToLower($user_agent);
		$l = strlen($nua);
		for ($i=0; $i<count($browsers); $i++){
			$browser = $browsers[$i];
			$n = stristr($nua, $browser);
			if(strlen($n)>0){
				$temp['ver'] = '';
				$temp['nav'] = $browser;
				$j=strpos($nua, $temp['nav'])+$n+strlen($temp['nav'])+1;
				for (; $j<=$l; $j++){
					$s = substr ($nua, $j, 1);
					if(is_numeric($temp['ver'].$s) )
						$temp['ver'] .= $s;
					else
						break;
				}
			}
		}
		if(isset($temp['nav']) && $temp['nav'] != ''){
			return array(
				'nav'=>$temp['nav'],
				'ver'=>$temp['ver'],
			);
		}else{
			return array(
				'nav'=>'unknown',
				'ver'=>'unknown',
			);
		}
	}
	
	public static function isSpider(){
		if(!isset($_SERVER['HTTP_USER_AGENT'])){
			return false;
		}
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		if (!empty($agent)) {
			$spiders = \F::app()->config->get('*', 'spiders');
			foreach($spiders as $val) {
				$str = strtolower($val);
				if (strpos($agent, $str) !== false) {
					return $val;
				}
			}
		}
		return false;
	}
	
	public static function getSearchEngine($refer = null){
		$refer === null && $refer = $_SERVER['HTTP_REFERER'];
		$parse_url = parse_url($refer);
		$data = array();
		if(isset($parse_url['host']) && isset($parse_url['query'])){
			parse_str($parse_url['query'], $output);
			if(strpos($parse_url['host'], '.soso.') !== false){
				//soso搜索
				$data['se'] = 'soso';
				if(isset($output['w'])){
					if(isset($output['ie']) && strtolower($output['ie']) != 'utf-8'){
						$data['keywords'] = iconv($output['ie'], 'UTF-8', urldecode($output['w']));
					}else{
						setcookie('refer', 'soso:'.urldecode($output['w']), $this->current_time + 86400 * 30, '/');
						$data['keywords'] = urldecode($output['w']);
					}
				}
			}else if(strpos($parse_url['host'], '.so.') !== false){
				//360搜索
				$data['se'] = '360';
				if(isset($output['q'])){
					if(isset($output['ie']) && strtolower($output['ie']) != 'utf-8'){
						$data['keywords'] = iconv($output['ie'], 'UTF-8', urldecode($output['q']));
					}else{
						$data['keywords'] = urldecode($output['q']);
					}
				}
			}else if(strpos($parse_url['host'], '.baidu.') !== false){
				//百度
				if(strpos($parse_url['host'], 'm.baidu') !== false){
					//百度手机
					$data['se'] = 'm.baidu';
					if(isset($output['ie']) && strtolower($output['ie']) != 'utf-8'){
						$data['keywords'] = iconv($output['ie'], 'UTF-8', urldecode($output['word']));
					}else{
						$data['keywords'] = urldecode($output['word']);
					}
				}else{
					//百度网页
					$data['se'] = 'baidu';
					if(isset($output['wd'])){
						$word = $output['wd'];
					}else if(isset($output['word'])){
						$word = $output['word'];
					}else{
						$word = '';
					}
					if(isset($output['ie']) && strtolower($output['ie']) != 'utf-8'){
						$data['keywords'] = iconv($output['ie'], 'UTF-8', urldecode($word));
					}else{
						$data['keywords'] = urldecode($word);
					}
				}
			}else if(strpos($parse_url['host'], '.google.') !== false){
				//谷歌搜索
				$data['se'] = 'google';
				if(isset($output['q'])){
					$data['keywords'] = urldecode($output['q']);
				}
			}
		}
		return $data;
	}
}