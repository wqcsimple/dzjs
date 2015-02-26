<?php
use fayfox\helpers\Date;
use fayfox\helpers\String;
?>
<tr>
	<td><?php echo $data['spider']?></td>
	<td><?php echo $data['url']?></td>
	<td><span class="abbr" title="<?php echo $data['user_agent']?>"><?php echo String::niceShort($data['user_agent'], 50)?></span></td>
	<td><span class="abbr" title="<?php echo long2ip($data['ip_int'])?>"><?php echo $iplocation->getCountry(long2ip($data['ip_int']))?></span></td>
	<td class="col-time"><span class="abbr" title="<?php echo Date::format($data['create_time'])?>"><?php echo Date::format($data['create_time'])?></span></td>
</tr>