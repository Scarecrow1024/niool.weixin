<?php

namespace Addons\English\Model;

use Home\Model\WeixinModel;

/**
 * English的微信模型
 */
class WeixinAddonModel extends WeixinModel {
	function reply() {
		$curl=curl_init("http://apix.sinaapp.com/daily/?appkey=trailuser&type=translation");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$output=curl_exec($curl);
		curl_close($curl);
		$enArr=json_decode($output,true);
		//print_r($arr);
		$enArr[0]['Title']="每日英语";
		$enArr[0]['Description'].="\n"."------------"."\n"."点击进入双语阅读";
		$enArr[0]['Url']="http://xue.youdao.com/zx/archives/category/practical/article";
        $this->replyNews($enArr);
	}
	
	// 关注公众号事件
	public function subscribe() {
		return true;
	}
	
	// 取消关注公众号事件
	public function unsubscribe() {
		return true;
	}
	
	// 扫描带参数二维码事件
	public function scan() {
		return true;
	}
	
	// 上报地理位置事件
	public function location() {
		return true;
	}
	
	// 自定义菜单事件
	public function click() {
		return true;
	}
}
        	