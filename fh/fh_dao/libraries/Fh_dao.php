<?php 
defined('BASEPATH') OR exit('No direct script access allowed');


class Fh_dao {
    
    private $descriptors=array();
    private $entities=array();
    private $token_js="123909DJDAOK";

    public function __construct($params= array()) {

	$this->CI =& get_instance();
	
	
	if (count($params) > 0):  //load other descriptor
	    $this->initialize($params); 
	else: //load descriptor by default
	    $this->CI->config->load('fh_descriptor'); 
	    $this->descriptors=$this->CI->config->item("descriptor");
	    $this->entities=$this->CI->config->item("entity");
	endif;
	    
	
	$this->CI->load->model("fhm_dao");

    }


    private function initialize($params = array())
    {
	if (count($params) > 0):
	
	    foreach ($params as $key => $val):
		if (isset($this->$key)):
		    $this->$key = $val;
		endif;
	    endforeach;
	endif;
    }
    
    
    
    public function get_fiche($params)
    {
	/* value default*/
	$type_view="v";
	$where=NULL;
	$indexes=$params["indexes"];
	$name_entity=$params["entity"];
	$descriptor=$this->get_descriptor($indexes);
	$entity=$this->entities[$name_entity];
	
	if(isset($params["type_view"])): $type_view=$params["type_view"]; endif;
	if(isset($params["where"])): $where=$params["where"]; endif;
	/* Recupere the date */
	$datas=$this->CI->fhm_dao->get_fiche_data($entity,$descriptor,$where);
	
	$data["labels"]=$this->get_label($entity, $descriptor);
	$data["values"]=$this->get_values($datas,$descriptor,$indexes);
	$data["token_js"]=$this->token_js;
	
	$view="";
	$view.=$this->CI->load->view("fh_dao_js",$data,TRUE);
	
	switch ($type_view):
	    case "v":
		$view.=$this->CI->load->view("fhv_dao_fiche_vertical",$data,TRUE);

	    case "h":

		
		break;
	endswitch;
	
	return $view;
    }
    
    public function get_liste($params,$start=0,$limit=50)
    {
	
	$indexes=$params["indexes"];
	$name_entity=$params["entity"];
	$descriptor=$this->get_descriptor($indexes);
	$entity=$this->entities[$name_entity];
	
	$datas=$this->CI->fhm_dao->get_liste_data($entity,$descriptor,$limit,$start);
	
	$data["labels"]=$this->get_label($entity, $descriptor,$indexes,$value);
	$data["values"]=$datas;

	return $this->CI->load->view("fhv_dao_liste",$data,TRUE);
    }
    
    public function get_form_insert()
    {
	
    }

    public function get_read($value,$index,$descriptor_index=NULL,$key_id_field=0)
    {
	if(is_null($descriptor_index)):
	    $descriptor_index=$this->descriptor[$index];
	endif;
	
	$key_js=$this->get_key_js($index,$descriptor_index["table"],$descriptor_index["field_sql"],$key_id_field);
	
	$data["key_js"]=$key_js;
	$data["value"]=$value;
	$data["token_js"]=$this->token_js;
	
	
	return $this->CI->load->view("fhv_dao_mode_lecture",$data,TRUE);

	
    }
    
  
    
    public function get_update($value,$index,$descriptor_index=NULL,$key_id_field=0)
    {
	$key_js=$this->get_key_js($index,$descriptor_index["table"],$descriptor_index["field_sql"],$key_id_field);
	
	$data["key_js"]=$key_js;
	$data["value"]=$this->get_input($value,$index,$descriptor_index,$key_id_field);
	$data["token_js"]=$this->token_js;
	
	return $this->CI->load->view("fhv_dao_mode_update",$data,TRUE);
	
    }
    
    
    public function get_input($value,$index,$descriptor_index=NULL,$key_id_field=0){
	
	$data["key_js"]=$this->get_key_js($index,$descriptor_index["table"],$descriptor_index["field_sql"],$key_id_field);
	$data["value"]=$value;
	$data["index"]=$index;
	$data["token_js"]=$this->token_js;
	$data["label"]=$descriptor_index["label"];
	$data["key_id_field"]=$key_id_field;
	
	switch ($descriptor_index["type_input"]) {
	    case "input":
		
		return $this->CI->load->view("fhv_dao_form_input",$data,TRUE);
	
	    case "select":
		$params["descriptor"]=$descriptor_index;
		$data["list_select"]=$this->CI->load->fhm_dao->get_list_select($params);
		return $this->CI->load->view("fhv_dao_form_select",$data,TRUE);

	    default:
		
		break;
	}
	
    }
    
    
    public function get_insert($index)
    {
	
    }
    
    public function get_error($index,$value)
    {
	
    }
    
    
    public function set_update()
    {
	$error=0;
	$msg_error="b";
	$new_value="c";
	$label_value="";
	
	$index=$this->CI->input->post("index");
	$contexte="update";
	
	$descriptor=$this->descriptors[$index];
	
	$params["value"]=trim($this->CI->input->post("value"));
	$params["key_id_field"]=$this->CI->input->post("key_id_field");
	
	
	
	//Error
	switch ($descriptor["type_input"]) {
	    case "select":
	    case "input":
		$error=$this->set_error($descriptor, $params["value"],$descriptor["type_input"]);
		$msg_error=$this->get_msg_error($error);
		break;
	    
	    case "input_email":
		
		break;
	}
	
	if($error==0):
	    //Update_insert data base
	    switch ($contexte) {
		case "update":
		    $this->CI->fhm_dao->update_data($descriptor,$params);
		    break;

		case "insert":
		    $params["key_id_field"]=$this->CI->fhm_dao->insert_data($descriptor,$params);
		    break;
	    }

	    //New value data base
	    switch ($descriptor["type_input"]) {
	    case "input":
		    $new_value=$this->CI->fhm_dao->read_one_data($descriptor,$params);
		    $label_value=$new_value;
		break;
	    
	    case "select":
		  if($this->CI->fhm_dao->read_one_data($descriptor,$params)==0):
		      $new_value=0;
		      $label_value="";
		  else:
		    $new_result=$this->CI->fhm_dao->read_one_data_select($descriptor,$params);
		    //print_r($new_result); exit();
		    $new_value=$new_result[0]->$descriptor["join_field_sql"];
		    $label_value=$new_result[0]->$descriptor["select_field_sql"];
		  endif;
		break;
	    
	    case "input_email":
		
		break;
	    }
	    
	endif;
	
	return $data=array(
	    "error"=>$error,
	    "msg_error"=>$msg_error,
	    "new_value"=>$new_value,
	    "label_value"=>$label_value
	    );
    }
    
    public function set_insert($descriptor)
    {
	
    }
    
    
    public function set_error($descriptor,$value,$type_input)
    {
	if(isset($descriptor["required"])&&$descriptor["required"]):
	    switch ($type_input) {
		case "select":
		case "input":
		    if(empty($value)):
			return 1;
		    endif;

		    break;

	    }
	endif;
	
	return 0;
	
    }
    
    public function get_msg_error($type=0)
    {
	switch ($type) {
	    case 1:
		return "Ce champ ne peut être vide!";

	}
	
	return "";
    }

    private function get_descriptor($indexes)
    {
	
	foreach($indexes as $index):
	    $descriptor[$index]=$this->descriptors[$index];
	endforeach;
	return $descriptor;
    }
    
    private function get_label($entity,$descriptor){
	
	$label=array();
	//$label=array($entity["key_label"]);
	
	foreach($descriptor as$d):
	    array_push($label, $d["label"]);
	endforeach;
	
	return $label;
    }
    
    private function get_values($datas,$descriptor,$indexes){
	$values=array();
	
	foreach($datas as $data):
		foreach($indexes as $index):
		    $key_id_field="id_".$index;
		    switch ($descriptor[$index]["type_input"]){
			case "input":
			    $view=$this->get_read($data->$index,$index,$descriptor[$index],$data->$key_id_field);
			    $view.=$this->get_update($data->$index,$index,$descriptor[$index],$data->$key_id_field);
			    array_push($values,$view);
			    break;
			case "select":
			    $value_select="select_".$index;
			 
			    $view=$this->get_read($data->$value_select,$index,$descriptor[$index],$data->$key_id_field);
			    $view.=$this->get_update($data->$index,$index,$descriptor[$index],$data->$key_id_field);
			    array_push($values,$view);
			    break;

			default:
			    break;
		    }
		   
		endforeach;
	endforeach;
	
	return $values;
    }
    
  
    
    private function get_key_js($index,$table,$field_sql,$key_id_field,$j=0)
    {
	return $index.$table.$field_sql.$key_id_field.$j;
    }
}
