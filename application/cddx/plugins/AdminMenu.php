<?php
namespace cddx\plugins;

use fayfox\core\FBase;

class AdminMenu extends FBase{
	public static function run(){
		if(method_exists(\F::app(), 'removeMenuTeam')){
			\F::app()->removeMenuTeam('goods');
			\F::app()->removeMenuTeam('voucher');
			\F::app()->removeMenuTeam('notification');
			\F::app()->removeMenuTeam('message');
			\F::app()->removeMenuTeam('bill');
			\F::app()->removeMenuTeam('template');
			\F::app()->removeMenuTeam('exam-question');
			\F::app()->removeMenuTeam('exam-paper');
		}
	}
}