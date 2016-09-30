<?php 
defined('BASEPATH') OR exit('No direct script access allowed');




class Fhl_welcome {
    
    var $name_project="lee rae";

    function __construct($params= array()) {

	if (count($params) > 0)
	{
		$this->initialize($params);
	}   
     // Assign the CodeIgniter super-object
     $this->CI =& get_instance();

    }


    public function get_welcome(){

	$data["name_project"]=$this->name_project;
    	return $this->CI->load->view("fhv_wel_hello",$data,TRUE);
    }

     function initialize($params = array())
    {
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			}
		}
    }


}
