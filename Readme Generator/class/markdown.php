<?php
class Markdown
{
	protected $content_path = CURRENT_DIR.'/sources/frameworks.json';
	protected $database_path = CURRENT_DIR.'/sources/categories.json';
	
	protected $static_header_path = CURRENT_DIR.'/sources/static_header.dat';
	protected $static_footer_path = CURRENT_DIR.'/sources/static_footer.dat';
	
	public function get_libraries(){
		$resource = file_get_contents($this->content_path);
		$data = json_decode($resource,true);
		foreach($data as $library){
			$result["$library[section_id]"][] = $library;
		}
		return $result;
	}
	
	public function get_sections(){
		$resource = file_get_contents($this->database_path);
		$data = json_decode($resource,true);
		
		foreach($data[0]['sections'] as $section){
			$exploded = explode('/',$section['path']);
			if(isset($exploded[1])){
				$section['path'] = $exploded[1];
				$navigation[$exploded[0]]['inner'][$exploded[1]] = $section;
				$navigation[$exploded[0]]['inner'][$exploded[1]]['title'] = $section['name'];
				unset($navigation[$exploded[0]]['inner'][$exploded[1]]['related_uikit_classes']);
			}else{
				$navigation[$exploded[0]] = $section;
				$navigation[$exploded[0]]['title'] = $section['name'];
				unset($navigation[$exploded[0]]['related_uikit_classes']);
			}
		}
		return $navigation;
	}
	
	public function section_by_id($id){
		$sections = $this->get_sections();
		$exploded = explode('/',$id);
		if(isset($sections["$exploded[0]"])){
			if(isset($exploded[1]) && isset($sections["$exploded[0]"]['inner']["$exploded[1]"])){
				return $sections["$exploded[0]"]['inner']["$exploded[1]"]['title'];
			}else{
				return $sections["$exploded[0]"]['title'];
			}
		}else{
			foreach($sections as $s){
				if(isset($s['inner'])){
					foreach($s['inner'] as $n=>$d){
						if($d['id']==$id){return $d['name'];}
					}
				}
			}
			return false;
		}
	}
	
	public function generate_md(){
		$header = file_get_contents($this->static_header_path);
		$footer = file_get_contents($this->static_footer_path);
		$md = $header.PHP_EOL.PHP_EOL;
		$md.= '## Navigate'.PHP_EOL.PHP_EOL;
		foreach($this->get_sections() as $section){
			$md.= '- ['.$section['title'].'](#'.$section['id'].')'.PHP_EOL;
			if(isset($section['inner'])){
				foreach($section['inner'] as $inner_section){
					$md.= '	- ['.$inner_section['title'].'](#'.$inner_section['id'].')'.PHP_EOL;
				}
			}
		}
		$md.= PHP_EOL.'## Content'.PHP_EOL;
		foreach($this->get_libraries() as $category_id=>$libraries){
			$section_name = $this->section_by_id($category_id);
			$md.= PHP_EOL.'### '.$section_name.''.PHP_EOL.PHP_EOL;
            foreach($libraries as $n=>$library){
                $md.= '* ['.$library['name'].']('.$library['source_url'].') - '.$library['description'].((isset($library['preview_url']) && $library['preview_url']!='')?' [•]('.$library['preview_url'].')':'').PHP_EOL;
            }
		}
		$md.= PHP_EOL.$footer;
		file_put_contents(CURRENT_DIR.'/Readme.md',$md);
	}
}
?>