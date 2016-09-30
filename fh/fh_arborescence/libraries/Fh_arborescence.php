<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Fh_arborescence
 *
 * @author absuur
 */
class Fh_arborescence {
    
    var $token="&é9àffiosfoosifos34";
    //Table de relation parent - enfant
    var $table="rubrique_spiroulie"; 
    var $champ_sql_id_parent="id_parent";
    var $champ_sql_id_enfant="id";
    
    //Table des noms parent-enfant
    var $table_nom="rubrique_spirouline"; 
    var $champ_sql_nom="nom"; 
    var $champ_sql_id_nom="id";
    
    //Si condition pour afficher une rubrique
    var $is_actif=TRUE; 
    var $champ_sql_actif="is_actif";
    
    //Si classement par un rank
    var $is_rank=TRUE; 
    var $champ_sql_rank="rank";
    
    public function __construct($params= array()) 
    {
	 //verifie si j'ai des parametres
	if (count($params) > 0)
	{
		$this->initialize($params);
	}   
	    
	$this->CI =& get_instance();
	$this->CI->load->model("Fhm_arborescence");

    }
    
    function params()
	{
	    $params["table"]=$this->table;
	    $params["champ_sql_id_parent"]=$this->champ_sql_id_parent;
	    $params["champ_sql_id_enfant"]=$this->champ_sql_id_enfant;
	    $params["table_nom"]=$this->table_nom;
	    $params["champ_sql_nom"]=$this->champ_sql_nom; 
	    $params["champ_sql_id_nom"]=$this->champ_sql_id_nom; 
	    $params["is_actif"]=$this->is_actif; 
	    $params["champ_sql_actif"]=$this->champ_sql_actif;
	    $params["is_rank"]=$this->is_rank; 
	    $params["champ_sql_rank"]=$this->champ_sql_rank;
	    $params["token"]=$this->token;
	  
	   
	    
	    return $params;
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
    
        
    public function get_arborescence($id_en_cours=0,$nom_entity="Rubrique",$position=0,$js_load=TRUE,$id_enfant=0,$is_append=0)
    {
	
	
	$data["enfants"]=$this->get_enfant_direct($this->params(),$id_en_cours,$id_enfant);
	$data["id_en_cours"]=$id_en_cours;
	$data["params"]=$this->params();
	$data['nom_parent']=$this->get_name_parent($id_en_cours);
	$data["nom_entity"]=$nom_entity;
	$data["position"]=$position;
	$data["is_append"]=$is_append;
	
	$view="";
	if($js_load):
	    $view.=$this->CI->load->view("fhv_arborescence_js",$data,TRUE);
	endif;
	$view.=$this->CI->load->view("fhv_arborescence_view",$data,TRUE);
	
	
	return $view;
    }

    
    private function get_enfant_direct($params,$id_en_cours,$id_enfant)
    {
	return $this->CI->Fhm_arborescence->get_enfant_direct($params,$id_en_cours,$id_enfant);
	
	
    }
    
    public function get_name_parent($id_en_cours)
    {
	if($id_en_cours>0):
	$nom=$this->CI->Fhm_arborescence->get_nom_parent($this->params(), $id_en_cours);
	
	$champ_sql_nom=$this->champ_sql_nom;
	return $nom[0]->$champ_sql_nom;
	else:
	    return "le premier niveau";
	endif;
    }
    
    
     public function insert_new($nom_value,$id_parent)
    {
	 return $this->CI->Fhm_arborescence->insert_new($this->params(), $nom_value,$id_parent);
	 
	
    }
 
   
              
}
