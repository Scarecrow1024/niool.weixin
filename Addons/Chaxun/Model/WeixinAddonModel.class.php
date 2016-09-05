<?php

namespace Addons\Chaxun\Model;

use Home\Model\WeixinModel;

/**
 * Chaxun的微信模型
 */
class WeixinAddonModel extends WeixinModel {
	function reply() {
		$openid=get_openid();
        $url1=addons_url ('Chaxun://Chaxun/login/openid/'.$openid);
        $url2=addons_url ('LinianScore://LinianScore/linian/openid/'.$openid);
        $url3=addons_url ('TiCe://TiCe/tice/openid/'.$openid);
        $dataArr[0]=array(
        		'PicUrl' => 'http://hpuepaper.cuepa.cn/newspics/2014/02/s_da77199b1c4273443a288b10a017dcac206833.jpg',
                'Title' => '河南理工大学成绩查询系统',
            );
        $dataArr[1]=array(
                'Title' => "本学期成绩",
                'PicUrl' => 'http://img5q.duitang.com/uploads/item/201506/15/20150615080039_xe43i.jpeg',
                'Url' => $url1
            );
        $dataArr[2]=array(
                'Title' => '历年成绩',
                'PicUrl' => 'http://1.aithinkingw.sinaapp.com/kaoshen.jpg',
                'Url' => $url2
            );
        $dataArr[3]=array(
                'Title' => '体育达标测试',
                'PicUrl' => 'http://s9.sinaimg.cn/orignal/4b848eba1cd7e84d73148',
                'Url' => $url3
            );
        $dataArr[4]=array(
                'Title' => "【温馨提示】\n历年成绩和体育达标测试需绑定后才能使用",
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
        	