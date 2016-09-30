<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fhc_arborescence extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
        {
           parent::__construct();
          //$this->output->enable_profiler(TRUE);
	   $this->load->add_package_path(APPPATH.'third_party/ForgeHammer/Fh_arborescence');
	  
            
        }
	
	
	public function get_arborescence($id=0,$nom_entity,$position,$id_enfant=0,$is_append=0)
	{
		$this->config->load('rubrique_spirouline'); 
		$params=$this->config->item("params");
		$this->load->library("Fh_arborescence",$params);
		echo $this->fh_arborescence->get_arborescence($id,$nom_entity,$position,FALSE,$id_enfant,$is_append);
	}
	
	
        
       public function insert_arborescence()
       {
	   $data=array();
	   $is_error=0;
	   $id_insert=0;
	    
		$this->config->load('rubrique_spirouline'); 
		$params=$this->config->item("params");
		$this->load->library("Fh_arborescence",$params);
	   
	   if(empty($this->input->post("nom_arbo"))):
	       $is_error=1;
	   else:
	        $nom_value=$this->input->post("nom_arbo");
		 $id_parent=$this->input->post("id_parent");
	
		 $id_insert=$this->fh_arborescence->insert_new($nom_value,$id_parent);
	   endif;
	   
	   
	   $data["is_error"]=$is_error;
	   $data["id_insert"]=$id_insert;
	   
	   echo json_encode($data);
	   flush();
	   
       }
 
        
        
        
        
   
}
