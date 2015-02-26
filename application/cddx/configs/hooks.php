<?php
return array(
	'after_controller_constructor'=>array(
		//Controller实例化后执行
		array(
			'router'=>'/^(admin)\/.*$/i',
			'function'=>'cddx\\plugins\\AdminMenu::run',
		),
		array(
			'router'=>'/^admin\/post\/(create|edit|index).*$/i',
			'function'=>'cddx\\plugins\\HideBoxes::run',
		),
	),
);