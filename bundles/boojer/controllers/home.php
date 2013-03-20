<?php

use Content\Models\Menuitem as Menuitem;
use \Laravel\Config as Config;

class Boojer_Home_Controller extends Boojer_Base_Controller {
    public function get_gallery()
    {
		$page = Menuitem::get_page_by_uri(Request::uri());
		
		if (!$page) {
			return Response::error('404');
		}

		$this->view_arguments['page_data'] = $page;

		$this->view_arguments['galleries'] = Boojer\Models\Album::get_albums(12);

		return View::make('Boojer::gallery', $this->view_arguments);
    }

    public function get_gallery_json($id = FALSE) {
    	$data = Boojer\Models\Album::get_by_id_with_photos_and_tags($id);
    	
    	$album['album'] = array();
    	if (!empty($data)) {
	    	$album['album']['name'] = $data->name;
	    	$album['album']['description'] = $data->description;
	    	$album['album']['votes'] = $data->votes;
	    	$album['album']['created_at'] = $data->created_at;
	    	$album['album']['slug'] = $data->slug;
	    	$album['album']['id'] = $data->id;
	    	$album['album']['photos'] = array();

	    	if (!empty($data->photos)) {
		    	foreach ($data->photos as $photo) {
		    		$tags = array();
		    		
		    		if (!empty($photo->tags)) {
			    		foreach ($photo->tags as $tag) {
			    			$tags[] = array(
			    				'name' => $tag->first_name . ' ' . $tag->last_name,
			    				'id' => $tag->id,
			    				'username' => $tag->username,
			    				'photo' => $tag->professional_photo_small
			    			);
			    		}
			    	}

		    		$album['album']['photos'][] = array(
		    			'id' => $photo->id,
		    			'url' => $photo->path,
		    			'thumb' => $photo->thumb_path,
		    			'votes' => $photo->votes,
		    			'caption' => $photo->caption,
		    			'date' => date('F j, Y', strtotime($photo->created_at)),
		    			'tags' => $tags
		    		);
		    	}
		    }
		}
    	return Response::json($album);
    }

    public function get_boojers()
    {
		$page = Menuitem::get_page_by_uri(Request::uri());
		
		if (!$page) {
			return Response::error('404');
		}

		$this->view_arguments['page_data'] = $page;
		$fun_tags = array();
		$pro_tags = array();

		$boojers = Boojer\Models\Boojer::get_boojers();
		if (!empty($boojers)) {
			foreach ($boojers as $boojer) {
				foreach ($boojer->tags as $tag) {
					if ($tag->type === 'professional') {
						$pro_tags[$tag->id] = $tag->name;
					}
					if ($tag->type === 'fun') {
						$fun_tags[$tag->id] = $tag->name;
					}
				}
			}
		}

		asort($fun_tags);
		asort($pro_tags);

		$this->view_arguments['fun_tags'] = $fun_tags;
		$this->view_arguments['pro_tags'] = $pro_tags;
		$this->view_arguments['boojers'] = $boojers;

		return View::make('Boojer::boojers', $this->view_arguments);
    }

    public function get_show_boojer($username = FALSE)
    {
		$user = Boojer\Models\Boojer::get_by_username($username);
		
		if (!$user) {
			return Response::error('404');
		}

		$this->view_arguments['page_data'] = Menuitem::get_page_by_uri(URI::segment(1));

		$view = Request::ajax() ? 'Boojer::view_boojer_ajax' : 'Boojer::view_boojer';

		$this->view_arguments['boojer'] = $user;

		return View::make($view, $this->view_arguments);
    }

    public function get_show_gallery($slug = FALSE)
    {
		$gallery = Boojer\Models\Album::get_album_by_slug($slug);

		if (!$gallery || empty($gallery[0])) {
			return Response::error('404');
		}

		$this->view_arguments['page_data'] = Menuitem::get_page_by_uri(URI::segment(1));
		$this->view_arguments['gallery'] = $gallery[0];

		return View::make('Boojer::view_gallery', $this->view_arguments);
    }
}