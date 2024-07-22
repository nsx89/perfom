<?php
namespace Custom;

use Custom\Admin;

class Paginate 
{
	function __construct($total) {
		$pnumber = self::pnumber();
		$query = self::query();
		
		$this->total=$total;
		$this->pnumber=$pnumber;
		$this->query=$query;
		if(!empty($_GET['page']))
		{
			$this->page = (int)$_GET['page'];
		}
		else
		{
			$this->page = 1;
		}
	}

	static function getList($query) {
		global $DB;

		$data = [];

		$page = Admin::processing($_GET['page']);

		$sres = $DB->Query($query);
	    $total = $sres->SelectedRowsCount();
	    if ($total) {
	        
	        $pnumber = self::pnumber();
	        $self = new self($total);

	        $res = $DB->Query($query." LIMIT ".$self->start().",$pnumber");
	        while ($row = $res->Fetch()) {
	            $list[] = $row;
	        }

	        $data['list'] = $list;
	        $data['paginate'] = $self->links();
	        $data['total'] = $total;
	    }
	    return $data;
	}

	//кол-во элементов на странице
	static function pnumber() {
		return 11;
	}

	static function query() {
		$uri = $_SERVER['REQUEST_URI'];
		$arr_uri = explode('?', $uri);
		$uri = $arr_uri[1];
		$page = Admin::processing($_GET['page']);
		$uri = str_replace('page='.$page, '', $uri);
		$uri = trim($uri, '&');
		if (!empty($uri)) $uri = '&'.$uri;
		return $uri;
	}

	function num_pages() {
		$this->num_pages=ceil($this->total/$this->pnumber);
		return $this->num_pages;
	}

	function start() {
		$this->num_pages=ceil($this->total/$this->pnumber);
		if ($this->page>$this->num_pages)
		{
			$this->page=$this->num_pages;
		}
		if (isset($_GET['last']))
		{
			$this->page=$this->num_pages;
		}
		$this->start=$this->page*$this->pnumber-$this->pnumber;
		if ($this->page > $this->num_pages || $this->page < 1)
		{
			$this->page=$this->num_pages;
		}
		return abs($this->start);
	}

	function links() {

		$arrows = '';
		if ($this->page > 1) {
			$arrows .= "<a class='main-ui-pagination-arrow main-ui-pagination-prev' href='".$_SERVER['SCRIPT_NAME']."?page=".($this->page-1).$this->query."'>предыдущая</a>";
		}
		else {
			$arrows .= "<span class='main-ui-pagination-arrow main-ui-pagination-prev' href='".$_SERVER['SCRIPT_NAME']."?page=".($this->page-1).$this->query."'>предыдущая</span>";
		}
		if ($this->page < $this->num_pages) {
			$arrows .= "<a class='main-ui-pagination-arrow main-ui-pagination-next' href='".$_SERVER['SCRIPT_NAME']."?page=".($this->page+1).$this->query."'>следующая</a>";
		}
		else {
			$arrows .= "<span class='main-ui-pagination-arrow main-ui-pagination-next'>следующая</span>";
		}
		if ($this->num_pages<2) {
			return "";
		}
		$main = '<div class="main-ui-pagination">
			<div class="main-ui-pagination-pages">
				<div class="main-ui-pagination-label">Страницы:</div>
				<div class="main-ui-pagination-pages-list">';
				for($pr = "", $i =1; $i <= $this->num_pages; $i++) {
					if($i == 1 || $i == $this->num_pages || abs($i-$this->page) < 7) {
						if($i == $this->page) {
							$pr = "<span class='main-ui-pagination-page main-ui-pagination-active'>".$i."</span>";
						}
						else {
							$pr = "<a class='main-ui-pagination-page' href='".$_SERVER['SCRIPT_NAME']."?page=".$i.$this->query."'>".$i."</a>";
						}
					}
					else {
						if($pr == "<div class='etc'>...</div>" || $pr == "") {
							$pr = "";
						}
						else {
							$pr = "<div class='etc'>...</div>";
						}
					}
					$main .= $pr;
				}
			$main .= '</div>
			</div>
			<div class="main-ui-pagination-arrows">'.$arrows.'</div>
		</div>';
		return $main;
	}
}