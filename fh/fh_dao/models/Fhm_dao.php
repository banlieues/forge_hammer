<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class fhm_dao extends CI_Model
{
	
	public function __construct() {
	    parent::__construct();
	   //$this->output->enable_profiler(TRUE); 
	}
    
	public function get_fiche_data($entity,$descriptor,$where,$count=FALSE)
	{
	    //Table de base
	    $this->db->from($entity["table"]);

	    //select
	    $this->db->select($this->set_select($entity,$descriptor));
	    
	   if(!is_null($where)):
		$this->db->where($where);
	   endif;
	   
	   if(!is_null($this->set_join($descriptor))):
	       foreach($this->set_join($descriptor) as $condition):
		    foreach($condition as $table_join=>$condition_join):
			$this->db->join($table_join,$condition_join,"left");
		    endforeach;
		endforeach;
	   endif;
	    
	    if($count):
		return $this->db->count_all_results();
	     else:         
		return $this->db->get()
                   ->result();
	    endif;
	}
	
	
	public function get_liste_data($entity,$descriptor,$limit,$start,$count=FALSE)
	 {
	    //Table de base
	    $this->db->from($entity["table"]);

	    //select
	    $this->db->select($this->set_select($entity,$descriptor));
	    
	    //Limit
	    $this->db->limit($limit,$start);
	    
	    if($count):
		return $this->db->count_all_results();
	     else:         
		return $this->db->get()
                   ->result();
	    endif;
	}
	
	
	
	public function update_data($descriptor,$params)
	{
	   
	    $this->db->where($descriptor["key"],$params["key_id_field"]);
	    $this->db->update($descriptor["table"],array($descriptor["field_sql"]=>$params["value"]));
	   
	}
	
	
	public function read_one_data($descriptor,$params)
	{
	    $this->db->select($descriptor["field_sql"]);
	    $this->db->where($descriptor["key"],$params["key_id_field"]);
	    $query=$this->db->get($descriptor["table"]);
	    foreach ($query->result() as $row):
		return $row->$descriptor["field_sql"];
	    endforeach;
	}
	
	public function read_one_data_select($descriptor,$params)
	{
	    $select=$descriptor["select_field_sql"];
	    $select.=" ,".$descriptor["join_field_sql"];
	    $this->db->select($select);
	    $this->db->where($descriptor["join_field_sql"],$params["value"]);
	    $this->db->from($descriptor["select_table"]);
	    return $this->db->get()
                   ->result();
	}
	
	public function get_list_select($params)
	{
	    
	    $select=$params["descriptor"]["join_field_sql"]." as value ";
	    $select.=", ".$params["descriptor"]["select_field_sql"]." as label ";
	    $this->db->select($select);
	    $this->db->from($params["descriptor"]["select_table"]);
	    
	    return $this->db->get()
                   ->result();
	
	}
	
	
	private function set_select($entity,$descriptor)
	{
	   
	    $select="";
	    //Select key
	    $select.=$this->set_entity_id($entity). ' as '.$entity["key_label"];
	   
	    foreach($descriptor as $k=>$d):
		
		 $select.=', ';
		 if($d["type_input"]=="input"):
		    $select.=$d["table"].'.'.$d["field_sql"].' as '.$k;
		    $select.=', ';
		    $select.=$d["table"].'.'.$d["key"].' as id_'.$k;
		 endif;
		 
		  if($d["type_input"]=="select"):
		    $select.=$d["select_table"].$k.'.'.$d["select_field_sql"].' as select_'.$k;
		    $select.=', ';
		    $select.=$d["table"].'.'.$d["field_sql"].' as '.$k;
		    $select.=', ';
		    $select.=$d["table"].'.'.$d["key"].' as id_'.$k;
		 endif;
		 
	    endforeach;
	    
	    return $select;
	}
	
	private function set_join($descriptor)
	{
	    $left=array();
	    foreach($descriptor as $k=>$d):
		$left_condition=array();
		if($d["type_input"]=="select"):
		    $index_left_condition=$d["select_table"]." as ".$d["select_table"].$k;
		    $left_condition[$index_left_condition]=$d["select_table"].$k.'.'.$d["join_field_sql"]."=".$d["table"].'.'.$d["field_sql"];
		    array_push($left,$left_condition);
		endif;
	    endforeach;
	    
	    if(empty($left)): 
		return NuLL; 
	    else: 
		return $left; 
	    endif;
	}
	
	private function set_entity_id($entity){
	    
	    return $entity["table"].'.'.$entity["key"];
	}
	
	
	//ANCIEN TRUC A EFFACER OU RECUPERER
	
	//Verification login
	public function verif_connex($id,$pass,$params)
	{
		
		$table=$params["table_users"];
		$name_user_sql=$params["name_users_sql"];
		$email_user_sql=$params["email_users_sql"];
		$function_encrypt=$params["function_encrypt"];
		$password_sql=$params["password_sql"];
		$is_valid_date=$params["is_valid_date"];
		
		if(!filter_var($id, FILTER_VALIDATE_EMAIL)){

			$this->db->where($name_user_sql, $id);
		}else{
			$email = strtolower($id);
			$this->db->where($email_user_sql, $email);
		}
	
		$query = $this->db->get($table);
		$row = $query->row();
			
		if($query->num_rows() == 1 ){
		 
		    switch ($function_encrypt) {
			case "ban_encrypt":
			    $is_valid_password=$this->ban_encrypt($pass, $row->$password_sql);
			    break;

			default:
			    $is_valid_password=password_verify($pass, $row->password_sql);
			    break;
		    }
 
		
		   if($is_valid_password){
			
				if($row->valid_date != NULL){
					return "success";

				}else{
				    if($is_valid_date):
					return "Veuillez valider votre compte via votre email!";
				    else:
					return "success";
				    endif;
				}
		    }else{
				return "Les informations ne correspondent pas";
		    }

	        }else{
				return "Les informations ne correspondent pas";
	       }

	}

	//verification l'existance d'un user
	public function isset_user($id,$params){

		if(!filter_var($id, FILTER_VALIDATE_EMAIL)){

			$this->db->where($params["name_users_sql"], $id);
		}else{
			$email = strtolower($id);
			$this->db->where($params['email_users_sql'], $email);
		}

		$query = $this->db->get($params["table_users"]);

		if($query->num_rows() == 1){
			return true;
		}else{
			return false;
		}
	}

	//return les données d'un user
    public function get_user($id,$params){

    	if(!filter_var($id, FILTER_VALIDATE_EMAIL)){

			$this->db->where($params["name_users_sql"], $id);
		}else{
			$email = strtolower($id);
			$this->db->where($params["email_users_sql"], $email);
		}

		$query = $this->db->get($params["table_users"]);

		return $query->row();
    }


	//Ajoute le token et la date pour reset 
	public function upuser($token, $email,$params){

	   $data = array (
	   		$params["reset_token_sql"] => $token,
	   	);
	   	
	   	$this->db->set($params["reset_date_sql"], 'NOW()', FALSE);
	    $this->db->where($params["email_users_sql"], $email);
		$this->db->update($params["table_users"], $data);

		return true;

	}

	//Insertion de nouveau utilisateur
	public function insert_user($username, $email, $pass, $token){

		$data = array(

			'username' => $username,
			'email' => $email,
			'password' => $pass,
			'valid_token' => $token,
		);
		
		$this->db->insert('users', $data);

		$id = $this->db->insert_id();
		$this->db->insert('profils_users', array('id_user'=>$id));

		return true;

	}

	//verification de token pour activate et reset
	public function confirm($ref, $token, $pseudo,$params){
	
		if($ref == "reset"){

			$this->db->where($params['reset_token_sql'], $token);
		    $this->db->where($params['name_users_sql'], $pseudo);


		}else if($ref == "activate"){

			$this->db->where($params['valid_token_sql'], $token);
		    $this->db->where($params['name_users_sql'], $pseudo); 
		}else{
			return false;
		}
		
		$query = $this->db->get($params['table_users']);

		if($query->num_rows() == 1){
			return true; 
		}else{
		return false;
		}

	}

	//verif time token
	public function token_time($time, $token, $pseudo, $params){

		$this->db->where($params['reset_token_sql'], $token);
		$this->db->where($params['name_users_sql'], $pseudo);
		$this->db->where("reset_date >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)", NULL, FALSE);

		$query = $this->db->get($params['table_users']);

		if($query->num_rows() == 1){
			return true; 
		}else{
		return false;
		}

	}

	public function is_token_reset($psorem,$params){

		$this->db->where("reset_date < DATE_SUB(NOW(), INTERVAL 30 MINUTE)", NULL, FALSE);
		$this->db->or_where("reset_date is NULL", NULL, FALSE);

		if(!filter_var($psorem, FILTER_VALIDATE_EMAIL)){

			$this->db->where($params["name_users_sql"], $psorem);
		}else{
			$email = strtolower($psorem);
			$this->db->where($params['email_users_sql'], $email);
		}

		$query = $this->db->get($params['table_users']);

		if($query->num_rows() == 1){
			return true; 
		}else{
		return false;
		}
	}

	//effectue les modifs necessaire pour reset et activate
	public function update($ref, $pseudo, $password,$params){
		if($ref == "reset"){
			$data = array (
	   		$params['reset_token_sql'] => NULL,
	   		$params['reset_date_sql'] => NULL,
	   		$params['password_sql'] => $password
	   	    );
		}

		if($ref == "activate"){

			$data = array (
	   		$params['valid_token_sql'] => NULL,
	   	    );
	   	
	     	$this->db->set($params['valid_date_sql'], 'NOW()', FALSE);
		}

		$this->db->where($params['name_users_sql'], $pseudo);
		$this->db->update($params['table_users'], $data);

		return true;
	}
	
	private function ban_encrypt($text,$password)
	{
           $encrypt_password=trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, "banlieues13conquerantdelalumiere", $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
	   if($encrypt_password==$password):
	       return TRUE;
	   else:
	       return FALSE;
	   endif;
	}


}


?>