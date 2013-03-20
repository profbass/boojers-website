<?php

class Tumbler_Home_Controller extends Tumbler_Base_Controller {
	public function get_tumbler()
	{
		$page = Content\Models\Menuitem::get_page_by_uri(Request::uri());
		
		if (!$page) {
			return Response::error('404');
		}

		$this->view_arguments['page_data'] = $page;
		$tumblers = Tumbler\Models\Tumbler::get_recent(20);

		$chunks = array();

		$i = 0;
		foreach($tumblers->results as $obj) {
		    $chunks[$i%4][] = $obj; 
		    $i++;
		}

		$this->view_arguments['raw_tumblers'] = $tumblers;
		$this->view_arguments['tumbler_chunks'] = $chunks;

		return View::make('tumbler::tumbler', $this->view_arguments);
	}
}