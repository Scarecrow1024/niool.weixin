<?php

namespace Addons\Chaxun;
use Common\Controller\Addon;

/**
 * Chaxun插件
 * @author 凡星
 */

    class ChaxunAddon extends Addon{

        public $info = array(
            'name'=>'Chaxun',
            'title'=>'成绩',
            'description'=>'本学期成绩查询插件',
            'status'=>1,
            'author'=>'niool',
            'version'=>'0.1',
            'has_adminlist'=>0,
            'type'=>1         
        );

	public function install() {
		$install_sql = './Addons/Chaxun/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Chaxun/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }