<?php

namespace Addons\Cet4\Model;

use Home\Model\WeixinModel;

/**
 * Cet4的微信模型
 */
class WeixinAddonModel extends WeixinModel {
	function reply() {
                $url='http://cet.redrock-team.com/#';
                $dataArr[0]=array(
                'Title' => '四六级成绩查询',
                'Description' => '免准考证查询四六级成绩',
                'PicUrl' => 'http://www.51offer.com/xinxi/uploadfile/2015/0716/20150716050523723.jpg',
                'Url' => $url
            );
                $this->replyNews($dataArr);
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
        	