<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Code 1 = Article public de une News
// Code 2= Autres articles public des News

class Fhm_arborescence extends CI_Model {
    
    
    public function get_enfant_direct($params,$id_encours,$id_enfant)
    {
	
	
	$table=$params["table"];
	$champ_sql_id_parent=$params["champ_sql_id_parent"];
	$champ_sql_id_enfant=$params["champ_sql_id_enfant"];
	$table_nom=$params["table_nom"];
	$champ_sql_nom=$params["champ_sql_nom"]; 
	$is_actif=$params["is_actif"]; 
	$champ_sql_actif=$params["champ_sql_actif"];
	$is_rank=$params["is_rank"]; 
	$champ_sql_rank=$params["champ_sql_rank"];
	
	if($table==$table_nom): $is_jointure_nom=FALSE; else: $is_jointure_nom=TRUE; endif;
	
	//Fields
	$fields="$table.$champ_sql_id_enfant as id_enfant, "
		. "$table.$champ_sql_id_parent as id_parent,";

	$fields.="$table_nom.$champ_sql_nom as nom_enfant,";
	
	$this->db->select($fields);
	
	//jointure
	
	//Where
	    
	    $where["$table.$champ_sql_id_parent"] = $id_encours;
	
	
	if(!is_null($is_actif)):
	     $where["$table_nom.$champ_sql_actif"] = 1;
	endif;
	
	if($id_enfant>0):
	    $where["$table.$champ_sql_id_enfant"] = $id_enfant;
	endif;
	
	
	if(isset($where)):
	    $this->db->where($where);
	endif;
	
	//Order
	if($is_rank):
	    $this->db->order_by("$table_nom.$champ_sql_rank");
	else:
	    $this->db->order_by("$table_nom.$champ_sql_nom");
	endif;
	
	//Execution
	$this->db->from($table);
	
	
	
	return $this->db->get()
                   ->result();
	

    }
    
    public function get_nom_parent($params,$id)
    {
	$this->db->from($params["table_nom"]);
	$this->db->select($params["champ_sql_nom"]);
	$where[$params["champ_sql_id_nom"]]=$id;
	$this->db->where($where);
	
	return $this->db->get()
                   ->result();
    }
    
    public function maxi_rank($id_parent,$params)
    {
	$this->db->from($params["table"]);
	$this->db->select('max('.$params["champ_sql_rank"].') as maxi');
	$where[$params["champ_sql_id_parent"]]=$id_parent;
	$this->db->where($where);
	
	$result=$this->db->get()
                   ->result();
	
	return $result[0]->maxi;
    }
    
    public function insert_new($params,$nom_value,$id_parent)
    {
	$table=$params["table_nom"];
	$nom_sql=$params["champ_sql_nom"];
	$id_parent_sql=$params["champ_sql_id_parent"];
	$is_rank=$params["is_rank"]; 
	$champ_sql_rank=$params["champ_sql_rank"];
	
	
	$data[$nom_sql]=$nom_value;
	$data[$id_parent_sql]=$id_parent;
	
	//Traitement s'il y a un rank
	if($is_rank):
	   $maxi_rank=$this->maxi_rank($id_parent,$params);
	    $maxi_rank=$maxi_rank+1;
	    $data[$champ_sql_rank]=$maxi_rank;
	endif;
	
	$this->db->insert($table,$data);
       $id_insert=$this->db->insert_id();
       return $id_insert;
	
    }
    
    
    //a jeter
   
   public function  read_data($table,$fields=NULL,$where=NULL,$left=NULL,$order=NULL,$group_by=NULL,$limit=NULL,$count=FALSE,$where_in=NULL,$where_not_in=NULL,$having=NULL)
   {
        //print_r($limit);
        if(!is_null($fields)):
            $this->db->select($fields);
        else:
            $this->db->select("*");
        endif;
        $this->db->from($table);
    
        if(!is_null($left)):
              
            if(!is_array($left[0])):
            $this->db->join($left[0],$left[1],"left");
            else:
              
                foreach($left as $k=>$v):
                    foreach($v as $ks=>$vs):
                        $this->db->join($ks,$vs,"left");
                    endforeach;
                endforeach;
            endif;
            
        endif;
        
        if(!is_null($where)):
           if(!is_array($where)):
               $this->db->where($where,NULL, FALSE);
           else:
                if(!is_array($where[0])):
                    $this->db->where($where[0],$where[1]);
                else:
                    foreach($where as $k=>$v):
                        foreach($v as $ks=>$vs):
                            $this->db->where($ks,$vs);
                        endforeach;
                    endforeach;
                endif;
          endif;  
        endif;
        
        if(!is_null($where_in)):
            $this->db->where_in($where_in[0],$where_in[1]);
        endif;
        
        if(!is_null($where_not_in)):
            $this->db->where_not_in($where_not_in[0],$where_not_in[1]);
        endif;
        
        
         if(!is_null($having)):
            $this->db->having($having);
        endif;
        
        if(!is_null($order)):
            $this->db->order_by($order);
        endif;
        
        if(!is_null($group_by)):
            $this->db->group_by($group_by);
        endif;
        
        if(!is_null($limit)):
             
            $this->db->limit($limit[0],$limit[1]);
        endif;
        
        if($count):
            return $this->db->count_all_results();
        else:
            
            return $this->db->get()
                   ->result();
        endif;
   }
   
   public function  search_data($table,$fields=NULL,$where=NULL,$left=NULL,$order=NULL,$group_by=NULL,$limit=NULL,$count=FALSE,$search_champ,$chaine,$option=1)
   {
       
        //ON prépare le moteur
        $chaine=trim($chaine);
        
            $chaine =preg_replace('/\s{2,}/', ' ', $chaine);
            $chaines=explode(" ",$chaine); 
            
           
       
         $is_new_search_champ=FALSE;
         
         $this->db->group_start();  
             foreach($search_champ as $vsc):
                if(count($chaines)>1):
                $this->db->group_start();
                else:
                    $this->db->or_group_start();
                endif;
                foreach($chaines as $vch):
                    $this->db->or_like($vsc,$vch);
            
                endforeach;
                $this->db->group_end();
                
            endforeach;
            
            
       $this->db->group_end();
           
       $this->db->or_group_start(); 
           foreach($search_champ as $vsc):
            $this->db->or_group_start();
                 foreach($chaines as $vch):
                     $this->db->like($vsc,$vch);
                 endforeach;
            $this->db->group_end();
           endforeach;
       $this->db->group_end();
       
       
       
       
        if(!is_null($fields)):
            $this->db->select($fields);
        else:
            $this->db->select("*");
        endif;
        $this->db->from($table);
    
        if(!is_null($left)):
              
            if(!is_array($left[0])):
            $this->db->join($left[0],$left[1],"left");
            else:
              
                foreach($left as $k=>$v):
                    foreach($v as $ks=>$vs):
                        $this->db->join($ks,$vs,"left");
                    endforeach;
                endforeach;
            endif;
            
        endif;
        
        if(!is_null($where)):
           if(!is_array($where)):
               $this->db->where($where,NULL, FALSE);
           else:
                if(!is_array($where[0])):
                    $this->db->where($where[0],$where[1]);
                else:
                    foreach($where as $k=>$v):
                        foreach($v as $ks=>$vs):
                            $this->db->where($ks,$vs);
                        endforeach;
                    endforeach;
                endif;
          endif;  
        endif;
        
        
        if(!is_null($order)):
            $this->db->order_by($order);
        endif;
        
        if(!is_null($group_by)):
            $this->db->group_by($group_by);
        endif;
        
        if(!is_null($limit)):
            $this->db->limit($limit[0],$limit[1]);
        endif;
        
        if($count):
            return $this->db->count_all_results();
        else:
            
            return $this->db->get()
                   ->result();
        endif;
   }
   
   public function  search_data_exact($table,$fields=NULL,$where=NULL,$left=NULL,$order=NULL,$group_by=NULL,$limit=NULL,$count=FALSE,$search_champ,$chaine,$option=1)
   {
       
        //ON prépare le moteur
        $chaine=trim($chaine);
        
            $chaine =preg_replace('/\s{2,}/', ' ', $chaine);
           
            
           
       
         $this->db->where($search_champ,$chaine);
       
       
       
       
        if(!is_null($fields)):
            $this->db->select($fields);
        else:
            $this->db->select("*");
        endif;
        $this->db->from($table);
    
        if(!is_null($left)):
              
            if(!is_array($left[0])):
            $this->db->join($left[0],$left[1],"left");
            else:
              
                foreach($left as $k=>$v):
                    foreach($v as $ks=>$vs):
                        $this->db->join($ks,$vs,"left");
                    endforeach;
                endforeach;
            endif;
            
        endif;
        
        if(!is_null($where)):
           if(!is_array($where)):
               $this->db->where($where,NULL, FALSE);
           else:
                if(!is_array($where[0])):
                    $this->db->where($where[0],$where[1]);
                else:
                    foreach($where as $k=>$v):
                        foreach($v as $ks=>$vs):
                            $this->db->where($ks,$vs);
                        endforeach;
                    endforeach;
                endif;
          endif;  
        endif;
        
        
        if(!is_null($order)):
            $this->db->order_by($order);
        endif;
        
        if(!is_null($group_by)):
            $this->db->group_by($group_by);
        endif;
        
        if(!is_null($limit)):
            $this->db->limit($limit[0],$limit[1]);
        endif;
        
        if($count):
            return $this->db->count_all_results();
        else:
            
            return $this->db->get()
                   ->result();
        endif;
   }
   
   
  
   public function insert_data($data,$table){
       
       $this->db->insert($table,$data);
       $id_insert=$this->db->insert_id();
       return $id_insert;
   }
   
   public function  update_data($table,$data,$where)
   {
        
        $this->db->update($table, $data,$where);
   }
   
   
   public function  delete_data($table,$where)
   {
        $this->db->where($where);
        $this->db->delete($table);
   }
   
   public function is_exist($table,$champ,$value){
        $this->db->select(array($champ));
        $this->db->from($table);
        $this->db->where($champ,$value);
        return $this->db->get()
                ->result();
    }
    
    public function verif_connexion($login,$mdp){

       $this->db->select("*");
       $this->db->from("users");
       $this->db->where("login",$login);
       $this->db->where("mdp",encrypt(trim($mdp)));
       $this->db->join("vignette","vignette.id_vignette=users.id_vignette","left");
       return $this->db->get()
               ->result();
   }
   
 
   public function hiear($id_parent=0)
   {
       $this->db->select("*");
       $this->db->from("ban_rubrique_spirouline");
      
           $this->db->where("id_parent",$id_parent);
           $this->db->where("niv_rub<>",3);
       
       return $this->db->get()
               ->result();
       
   }
   
   
   public function one_hiear($id_hiear)
   {
       $this->db->select("*");
       $this->db->from("ban_rubrique_spirouline");
       
           $this->db->where("id_hiear",$id_hiear);
   
       return $this->db->get()
               ->result();
       
   }
  
   
   public function results($chaine=NULL)
   {
       $this->db->select("*");
       $this->db->from("noo_cts_fiche");
       $this->db->where("id_fiche>",500);
       return $this->db->get()
               ->result();
   }
   
   
}


