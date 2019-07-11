<?php
namespace app\models;
use Yii;

class parseOverview{
	private $page = null;
	private $sort_values = ['список игроков', 'сроки', 'организация', 'обзор', 'история'];
	private $blocks = null;

	function __construct($id, $game){
		if(!empty($id)){
			$this->id = $id;
			$this->game = $game;
			$this->page = DotaHelper::httpGet(DotaHelper::tlBuilder($this->id, $this->game));
			$this->blocks = $this->parse();
		}else throw new Exception("Ошибка!");
	}
	public function getSql(){
		$sql = '';
		if(!empty($this->blocks)){
			$sql .= Yii::$app->db->createCommand()->delete('teams_players_about', 'id = "'.$this->id.'" AND game = '.$this->game)->getRawSql().'; ';
			foreach ($this->blocks as $block) {
				$block['id'] = $this->id;
				$block['game'] = $this->game;
				$sql .= Yii::$app->db->createCommand()->insert('teams_players_about', $block)->getRawSql().'; ';
			}
		}
		return $sql;
		
	}
	private function sort_names($name){
		for($i = 0; $i < count($this->sort_values); $i++){
			if(preg_match('/'.$this->sort_values[$i].'/', mb_strtolower($name, 'UTF-8'))){
				return $i;
			}
		}
		return count($this->sort_values);
	}
	private function parse(){
		if(!empty($this->page)){

			//Подготовка к парсингу
			$page = preg_replace('/<!--.+?-->/s', '', $this->page);
			$page = \phpQuery::newDocumentHTML($page)->find('#mw-content-text');
			$page->find('h2 span')->eq(0)->parent('h2')->prevAll()->remove();
			$page->find('.thumb')->remove();
			
			//Вкладки
			$tabs = $page->find('.tabs-dynamic');
			for ($i = 0; $i < count($tabs); $i++) {
				$tab = $tabs->eq($i)->find('.nav-tabs')->find('li');
				$content = $tabs->eq($i)->find('.tabs-content div[class^=content]');
				for ($g = 0; $g < count($tab); $g++) { 
					$class = $tab->eq($g)->attr('class');
					if(preg_match('/tab/', $class)){
						$text = $tab->eq($g)->text();
						$content->eq($g)->prepend('<h6>'.$text.'</h6>');
					}
				}
				$tabs->eq($i)->find('.nav-tabs')->remove();
			}


			$page->find('sup')->remove();
			$imga = $page->find('a img');
			for ($i = 0; $i < count($imga); $i++) { 
				$img = $imga->eq($i)->parent('a')->html();
				$imga->eq($i)->parent('a')->replaceWith($img);
			}
			$h = $page->find('h2');
			for ($i = 0; $i < count($h); $i++) { 
				if(preg_match('/highlight videos|references|achievements|gallery|awards|links|interviews|highlights/is', $h->eq($i)->text())){
					$next = $h->eq($i)->nextAll();
					$h->eq($i)->remove();
					for ($g = 0; $g < count($next); $g++) { 
						if(!$next->eq($g)->is('h2')){
							$next->eq($g)->remove();
						}else{
							break;
						}
					}
				}
			}
			$img = $page->find('img');
			for ($i = 0; $i < count($img); $i++) { 
				$link = $img->eq($i)->attr('src');
				$n = DotaHelper::getImage(['link' => $link, 'name' => preg_replace('/\//', '_', preg_replace('/^http:\/\/wiki\.teamliquid\.net\/.+?\/(.+?)$/', '$1', trim($link)))]);
				$img->eq($i)->attr('src', $n);
			}
			$at = $page->find('table')->find('a');
			for ($i = 0; $i < count($at); $i++) { 
				$text = $at->eq($i)->text();
				$at->eq($i)->replaceWith($text);
			}
			$a = $page->find('a');
			$buffer_m = [];
			$counter_m = 0;
			for ($i = 0; $i < count($a); $i++) { 
				$text = $a->eq($i)->text();
				$buffer_m['coram-'.$counter_m] = $text;
				$a->eq($i)->replaceWith('<!--coram-'.$counter_m.'-->');
				$counter_m++;
			}
			$tables = $page->find('table');
			for ($i = 0; $i < count($tables); $i++) { 
				$text = $tables->eq($i)->html();

				$buffer_m['coram-'.$counter_m] = '<table>'.$text.'</table>';
				$tables->eq($i)->replaceWith('<!--coram-'.$counter_m.'-->');
				$counter_m++;


			}
			$edit = $page->find('.mw-editsection');
			for ($i = 0; $i < count($edit); $i++) { 
				$edit->eq($i)->remove();
			}

			$html = preg_replace('/\n/', '', $page->html());
			$html = preg_replace('/;/', '', $html);

			
			$index = 0;
			$buffer = '';

			$counter = 0;

			while($counter < strlen(trim($html))){
				$str = substr(trim($html), $counter, 10000);
				if(substr_count($str, '<!--') > substr_count($str, '-->')){
					$str = substr($str, 0, strripos($str, '<!--'));
				}else if(substr_count($str, '<') > substr_count($str, '>')){
					$str = substr($str, 0, strripos($str, '<'));
				}

				$counter += strlen($str);

				//echo $str;
				$buffer .= LoadData::translate($str);

			}
			$buffer = preg_replace_callback('/<!--(coram-\d+?)-->/', function($sub) use($buffer_m){
				return $buffer_m[$sub[1]];
			}, $buffer);

			$buffer = preg_replace('/\'/', "\'", $buffer);
			$blocks = preg_split("/<h2>(.+?)<\/h2>/s", $buffer, -1, PREG_SPLIT_DELIM_CAPTURE);
			$dat = [];
			for ($i = 1; $i < count($blocks); $i += 2) {
				$text = $blocks[$i + 1];
				if(!empty($text)){
					$name = preg_replace('/^<span.+?>(.+?)<\/span>/', '$1', trim($blocks[$i]));

					$name = preg_replace('/<.+>/', '', $name);
					array_push($dat, ['name' => htmlspecialchars($name), 'text' => htmlspecialchars($text), 'sort' => $this->sort_names($name)]);
				}
				
			}
			return $dat;

		}
		return null;
		

	}
}


?>