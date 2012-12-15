<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: mobileUI.php
 */
class mobileUI{
	static function table($linearData,$colspan,$classname="") {
		$html = '<table class="'.$classname.'">';
		$total = ceil(count($linearData) / $colspan) * $colspan;
		for($i = 0; $i < $total; $i++){
			if(($i % $colspan) === 0){
				$html .= '<tr>';
			}
			if(isset($linearData[$i])){
				$html .=	'<td>'.$linearData[$i].'</td>';
			}else{
				$html .= '<td></td>';	
			}
			if(($i +1) % $colspan === 0 ){
				$html .= '</tr>';
			}
		}
		return $html.'</table>';
	}	
	static function ul($linearData,$colspan,$classname="") {
		$html = '<ul class="'.$classname.'">';
		$total = count($linearData);
		for($i = 0; $i < $total; $i++){
			$html .= '<li>'.$linearData[$i].'</li>';
		}
		return $html.'</ul>';
	}
	static function ol($linearData,$colspan,$classname="") {
		$html = '<ol class="'.$classname.'">';
		$total = count($linearData);
		for($i = 0; $i < $total; $i++){
			$html .= '<li>'.$linearData[$i].'</li>';
		}
		return $html.'</ol>';
	}
}