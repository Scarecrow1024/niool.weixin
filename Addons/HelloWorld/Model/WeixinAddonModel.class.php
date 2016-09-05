<?php

namespace Addons\HelloWorld\Model;

use Home\Model\WeixinModel;

/**
 * HelloWorld的微信模型
 */
class WeixinAddonModel extends WeixinModel {
	function reply() {
        $dataArr[0]=array(
                'Title' => 'lll',
                'Description' => 'sadasda',
                'PicUrl' => 'http://idoubi-wordpress.stor.sinaapp.com/uploads/2015/05/1.png',
                'Url' => 'www.baidu.com'
            );
        $dataArr[1]=array(
                'Title' => 'lll',
                'Description' => 'sadasda',
                'PicUrl' => 'http://idoubi-wordpress.stor.sinaapp.com/uploads/2015/05/1.png',
                'Url' => 'www.baidu.com'
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
        	