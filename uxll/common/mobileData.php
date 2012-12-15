<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: mobileData.php
 */
class mobileData extends CModel{
	public function channel($vm,$pos,$count,$artical_count=5){
		//strpos($mystring, $findme);
		$ret = array();
		$rs = $this -> table('channel') -> fields("`pic`,`alt`,`text`,`link`") 
		-> where("`location` LIKE '%".$pos."%'")
		-> order("`order` asc")
		-> limit('0,'.$count)
		-> select() -> all();
		$cur = 0;
		foreach($rs as $row){
			$ret[$cur] = array(
				'link' => $row['link'],
				'text' => $row['text']
			);
			if(false !== strpos($vm,'list')){
				$ret[$cur]['list'] = $this -> table('artical') -> fields("`title`,`urlkey`,`time`")
				-> where("`channel`='".$row['link']."'")
				-> order("`order` asc")
				-> limit('0,'.$artical_count)
				-> select() -> all();
			}
			if(false !== strpos($vm,'pic')){
				$ret[$cur]['alt'] = $row['alt'];
				$ret[$cur]['pic'] = $row['pic'];
			}	
			$cur++;		
		}	
		R('CHANNEL',$ret);	
		return $ret;
		
	}
	public function artical($channel,$listnumofpage=30,$fields=""){
		if(!$fields)$fields = "`title`,`urlkey`";
		$r = $this -> table('artical') -> fields($fields) 
		-> order('`order` asc,`id` desc') 
		-> where("`channel`='".$channel."'") 
		-> page(5,$listnumofpage);
		return $r -> select() -> all();
	}
	static public function oparr($mode,$arr,$item){
		switch($mode){
			case 'push':
				array_push($arr,$item);
				return $arr;
			case 'unshift':
				array_unshift($arr,$item);
				require $arr;
			case 'pop':	
				array_pop($arr);
				return $arr;
			case 'shift':	
				array_shift($arr);
				return $arr;
			case 'merge':
				return array_merge($arr,$item);
		}
	}
	static public function display($vm,$iconchannel,$row=5){
		switch($vm){
			case 'pic':
				$html = '';
				foreach($iconchannel as $iconmenu){
					$html .= '
					<div class="icon">
						<div class="icon-pic">
							<a href="'.mobileData::linkx($iconmenu['link']).'"><img src="'.$iconmenu['pic'].'" width=41 height=41 alt="'.$iconmenu['alt'].'" title="'.$iconmenu['alt'].'"></a>
						</div>
						<div class="icon-text"><a href="'.$iconmenu['link'].'">'.$iconmenu['text'].'</a></div>
					</div>	';				
				}
				return $html;

			case 'list':
				$html = '';
				foreach($iconchannel as $iconmenu){
					$html .= '
					<div class="list">
						<div class="text">'.$iconmenu['text'].'</div>
						<ul>
					';
					foreach($iconmenu['list'] as $alist){
						$html .= '<li><a href="'.mobileData::linkx($alist['urlkey'],$iconmenu['link']).'">'.$alist['title'].'</a></li>';
					}	
							
					$html .= '		
						</ul>
					</div>	';				
				}
				return $html;
			case 'list-time':
				$html = '';
				foreach($iconchannel as $iconmenu){
					$html .= '
					<div class="list">
						<div class="text">'.$iconmenu['text'].'</div>
						<ul>
					';
					foreach($iconmenu as $alist){
						$html .= '<li><a href="'.mobileData::linkx($alist['urlkey']).'">'.$alist['text'].'</a><span class="time">'.$alist['time'].'</span></li>';
					}	
							
					$html .= '		
						</ul>
					</div>	';				
				}
				return $html;
			case 'table':
				$html = '<table>';
				$total = ceil(count($iconchannel) / $row) * $row;
				for($i = 0; $i < $total; $i++){
					if(($i % $row) === 0){
						$html .= '<tr>';
					}
					if(isset($iconchannel[$i])){
						$html .=	'<td><a href="'.mobileData::linkx($iconchannel[$i]['link']).'">'.$iconchannel[$i]['text'].'</a></td>';
					}else{
						$html .= '<td></td>';	
					}
					if(($i +1) % $row === 0 ){
						$html .= '</tr>';
					}
				}
				return $html.'</table>';
			case 'icon-table':
				$html = '<table>';
				$total = ceil(count($iconchannel) / $row) * $row;
				for($i = 0; $i < $total; $i++){
					if(($i % $row) === 0){
						$html .= '<tr>';
					}
					if(isset($iconchannel[$i])){
						$html .=	'<td>
							<div class="icon">
								<div class="icon-pic">
								<a href="'.mobileData::linkx($iconchannel[$i]['link']).'"><img src="'.$iconchannel[$i]['pic'].'" width=41 height=41 alt="'.$iconchannel[$i]['alt'].'" title="'.$iconchannel[$i]['alt'].'"></a>
								</div>
								<div class="icon-text">
									<a href="'.mobileData::linkx($iconchannel[$i]['link']).'">'.$iconchannel[$i]['text'].'</a>
								</div>
							</div>
						</td>';
					}else{
						$html .= '<td></td>';	
					}
					if(($i +1) % $row === 0 ){
						$html .= '</tr>';
					}
				}
				return $html.'</table>';
				
			case 'icon-list':
				$html = '<div class="icon-list">
						<table>';
				foreach($iconchannel as $iconmenu){
					$html .= '
					
						<tr><td>
						<div class="icon-text"><a href="'.mobileData::linkx($iconmenu['link']).'"><img src="'.$iconmenu['pic'].'"></a></div>
						<div class="icon-text"><a href="'.mobileData::linkx($iconmenu['link']).'">'.$iconmenu['text'].'</a></div>
						</td><td>
						<ul>
					';
					foreach($iconmenu['list'] as $alist){
						$html .= '<li><a href="'.mobileData::linkx($alist['urlkey'],$iconmenu['link']).'">'.$alist['title'].'</a></li>';
					}	
							
					$html .= '		
						</ul></td></tr>	';				
				}
				$html .= '</table>
					</div>';
				return $html;
				
			
		}

	}
	static private function linkx($link,$action=''){
		if(preg_match("/^\w+$/", $link)){
			if($action)return "/".$action.'/'.$link;	
			return '/'.$link;
		}
		return $link;
	}
}