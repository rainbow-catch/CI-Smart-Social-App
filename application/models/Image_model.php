<?php

class Image_Model extends CI_Model 
{

	public function get_user_albums_sample($userid) 
	{
		return $this->db->where("user_albums.userid", $userid)
			->select("user_images.file_name, user_images.ID as imageid,
				user_albums.ID, user_albums.name, user_albums.description")
			->join("user_images", "user_images.albumid = user_albums.ID", "left outer")
			->group_by("user_albums.ID")
			->limit(4)
			->get("user_albums");
	}

    public function get_page_albums_sample($pageid) 
    {
        return $this->db->where("user_albums.pageid", $pageid)
            ->select("user_images.file_name, user_images.ID as imageid,
                user_albums.ID, user_albums.name, user_albums.description")
            ->join("user_images", "user_images.albumid = user_albums.ID", "left outer")
            ->group_by("user_albums.ID")
            ->limit(4)
            ->get("user_albums");
    }

	public function get_user_feed_album($userid) 
	{
		return $this->db
			->where("user_albums.userid", $userid)
			->where("feed_album", 1)
			->select("user_albums.ID, user_albums.name, user_albums.description")
			->get("user_albums");
	}

	public function add_album($data) 
	{
		$this->db->insert("user_albums", $data);
		return $this->db->insert_ID();
	}

	public function get_total_user_albums($userid) 
	{
		$s = $this->db->select("COUNT(*) as num")
			->where("userid", $userid)->get("user_albums");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_user_albums_all($userid) 
	{
		return $this->db->where("userid", $userid)->get("user_albums");
	}

	public function get_user_albums($userid, $datatable) 
    {
    	$datatable->db_order();

		$datatable->db_search(array(
			"user_albums.name"
			),
			true // Cache query
		);
		$this->db
			->where("user_albums.userid", $userid)
			->select("user_images.file_name, user_images.ID as imageid,
				user_albums.ID, user_albums.name, user_albums.description,
				 user_albums.images, user_albums.timestamp")
			->join("user_images", "user_images.albumid = user_albums.ID", "left outer")
			->group_by("user_albums.ID");
		return $datatable->get("user_albums");
    }

    public function delete_album($id) 
    {
    	$this->db->where("ID", $id)->delete("user_albums");
    }

    public function delete_album_images($id) 
    {
    	$this->db->where("albumid", $id)->delete("user_images");
    }

    public function get_user_album($id) 
    {
    	return $this->db->where("ID",$id)->get("user_albums");
    }

    public function update_user_album($id, $data) 
    {
    	$this->db->where("ID", $id)->update("user_albums", $data);
    }

    public function increase_album_count($id) 
    {
    	$this->db->where("ID", $id)
    		->set("images", "images+1", FALSE)->update("user_albums");
    }

    public function decrease_album_count($id) 
    {
    	$this->db->where("ID", $id)
    		->set("images", "images-1", FALSE)->update("user_albums");
    }

    public function get_album_images($id, $page) 
    {
    	return $this->db->where("albumid", $id)
    		->limit(50, $page)->get("user_images");
    }

    public function get_total_album_images($id) 
    {
    	return $this->db->where("albumid", $id)
    		->count_all_results("user_images");
    }

    public function add_image($data) 
    {
    	$this->db->insert("user_images", $data);
    	return $this->db->insert_ID();
    }

    public function get_image($id) 
    {
    	return $this->db->where("user_images.ID", $id)
            ->select("user_images.ID, user_images.name, user_images.description,
                user_images.userid, user_images.file_name, user_images.file_type,
                user_images.extension, user_images.file_size, user_images.timestamp,
                user_images.file_url, user_images.albumid,
                user_albums.pageid")
            ->join("user_albums", "user_albums.ID = user_images.albumid")
            ->get("user_images");
    }

    public function delete_image($id) 
    {
    	$this->db->where("ID", $id)->delete("user_images");
    }

    public function update_image($id, $data) 
    {
    	$this->db->where("ID", $id)->update("user_images", $data);
    }

    public function get_page_feed_album($pageid) 
    {
        return $this->db->where("pageid", $pageid)->get("user_albums");
    }

    public function get_total_page_albums($pageid) 
    {
        return $this->db->where("pageid", $pageid)
            ->from("user_albums")->count_all_results();
    }

    public function get_page_albums($pageid, $datatable) 
    {
        $datatable->db_order();

        $datatable->db_search(array(
            "user_albums.name"
            ),
            true // Cache query
        );
        $this->db
            ->where("user_albums.pageid", $pageid)
            ->select("user_images.file_name, user_images.ID as imageid,
                user_albums.ID, user_albums.name, user_albums.description,
                 user_albums.images, user_albums.timestamp")
            ->join("user_images", "user_images.albumid = user_albums.ID", "left outer")
            ->group_by("user_albums.ID");
        return $datatable->get("user_albums");
    }

    public function get_page_albums_all($id) 
    {
        return $this->db->where("pageid", $id)->get("user_albums");
    }

    public function get_total_user_images($userid) 
    {
        return $this->db->where("userid", $userid)->from("user_images")->count_all_results();
    }

}

?>