<?php
namespace app\models;
use yii\db\Query;
use Yii;
use yii\helpers\Html;

class WikiParse{

	
	const FINDING = 0;
	const OPEN = 1;

	const NAME = 0;
	const ATTR = 1;

	private $blocks = [];
	function __construct($text){
		$this->wikitext = $text;
	}

	public function parse($text){
		$i = 0;
		$buffer = '';
		$status_finder = self::FINDING;
		$status_attr = self::NAME;
		
		$queue = 0;
		$returns = [];
		$buffer_all = '';
		$name;
		$options = [];		
		while($i < strlen($text)){
			$char = $text[$i];
			if($status_finder == self::OPEN){
				$buffer_all .= $char;
			}
		 	switch ($char) {
		 		case '{':
		 			if($status_finder == self::FINDING){
		 				if($text[$i + 1] == $char){
		 					$status_finder = self::OPEN;
		 					$buffer = '';
		 					$buffer_all .= $char;
		 					$buffer_all .= $char;
		 					$i++;
		 				}
		 			}else if($status_finder == self::OPEN){
		 				if($text[$i + 1] == $char){
		 					$queue++;
		 					$i++;
		 					$buffer .= $char;
		 					$buffer_all .= $char;
		 					
		 				}
		 				$buffer .= $char;

		 			}
		 			break;
		 		case '}':
		 			if($queue > 0){
		 				if($text[$i + 1] == $char){
		 					$queue--;
		 					$i++;
		 					$buffer .= $char;
		 					$buffer_all .= $char;
		 				}
		 				$buffer .= $char;
		 			}else{
		 				if($text[$i + 1] == $char){
		 					if($status_attr == self::NAME){
		 						$name = $buffer;
		 					}else{
		 						if(preg_match('/^(.+?)=(.*?)$/', trim($buffer), $val)){
		 							$options[$val[1]] = $val[2];
		 						}else{
		 							$options['value'] = $buffer;
		 						}

		 					}
		 					$buffer_all .= $char;
		 					$status_finder = self::FINDING;
		 					$status_attr = self::NAME;
		 					$buffer = '';
							array_push($returns, ['name' => $name, 'options' => $options, 'text' => $buffer_all]);
							$options = [];
							$name = '';
							$buffer_all = '';
		 				}

		 			}
		 			break;
		 		case '|':
		 			if($status_attr == self::NAME){
		 				$status_attr = self::ATTR;
		 				$name = $buffer;
		 				$buffer = '';
		 			}else if($status_attr == self::ATTR){
		 				if($queue > 0){
		 					$buffer .= $char;
		 				}else{
		 					if(preg_match('/^(.+?)=(.*?)$/', trim($buffer), $val)){
		 						$options[$val[1]] = $val[2];
		 					}else{
		 						$options['value'] = $buffer;
		 					}
		 					
		 					$buffer = '';
		 				}
		 			}
		 			break;
		 		default:

		 			$buffer .= $char;
		 			break;
		 	}
		$i++;
		
		}
		return $returns;
	}
	private function makeTable($fileds){
		$rows = ['flag' => [], 'person' => []];
		for ($i = 0; $i < count($fileds); $i++) { 
			foreach ($fileds[$i] as $key => $value) {
				if($key != 'link'){
				if(!isset($rows[$key])){
					$rows[$key] = [];
				}
				array_push($rows[$key], $value);
				}
			}
		}
		echo '<table><thead>';
		foreach ($rows as $key => $value) {
			if(count($value) == count($fileds)){
				echo '<th>'.$key.'</th>';
			}else{
				unset($rows[$key]);
			}
		}
		echo '</thead><tbody>';
		for ($i = 0; $i < count($fileds); $i++) { 
			echo '<tr>';
			foreach ($rows as $key => $value) {
				$value[$i] = empty($value[$i]) ? '-' : $value[$i];
				if($key == 'flag'){
					echo '<td>'.Html::img('/img/flags/'.$value[$i].'.png').'</td>';
					
				}else echo '<td>'.$value[$i].'</td>';
			}
			echo '</tr>';
		}
		echo '</tbody></table>';
	}
	public function getRes(){
		$data = $this->parse($this->wikitext);
		$buffer = ['text' => ''];
		$res = [];
		$status = self::FINDING;
		//print_r($data);
		foreach ($data as $value) {
			switch (trim($value['name'])) {
				case 'Organization start':
				case 'Former organization start':
					$status = self::OPEN;
					$buffer['text'] .= $value['text'];
					$buffer['fileds'] = [];
					break;
				case 'Organization end':
					$status = self::FINDING;
					$buffer['text'] .= $value['text'];
					array_push($res, $buffer);
					$buffer = ['text' => ''];
					break;
				default:
					array_push($buffer['fileds'], $value['options']);
					$buffer['text'] .= $value['text'];
					break;
			}
		}
		$this->makeTable($res[0]['fileds']);
		$this->makeTable($res[1]['fileds']);
		//print_r($res);
		// print_r($data);
	}
}


?>