<?php class departamento extends CI_Model{

	var $id;  
	var $name; 
	var $descripcion;   

	function __construct()
	{
		// Llamando al contructor del Modelo
		parent::__construct();
		$this->carrera = $this->session->userdata('carrera');
		$this->pensum = $this->session->userdata('pensum');
		$this->semestre = $this->session->userdata('semestre');
	}
	

	function get_all()
	{
		$query = $this->db->query('SELECT * from departamento ');
		$row = $query->result_array();
		return $row;
	}

	function get_pensum($carrera){
		$query = $this->db->query('SELECT id from pensum where carrera_id ='.$carrera.' and estatus="ACTIVO"');
		$query = $query->result_array();
		return $query[0]['id'];
	}

	function get_horario(){
		$query = $this->db->query('SELECT * from view_horario where pensum ="'.$this->pensum.'" and semestre="'.$this->semestre.'"');

		return $query->result();
	}

	function get_profesor_full(){
		$get_prof = $this->db->query('SELECT DISTINCT u.ci, u.nombre, u.apellido from usuario u, docente_has_materia dhm, materia_has_pensum mhp where u.tipo=2 and dhm.usuario_ci = u.ci and mhp.materia_codigo = dhm.materia_codigo and mhp.semestre="'.$this->semestre.'" and mhp.pensum_id ="'.$this->pensum.'"');

		$result = array('data'=>array());
		foreach ($get_prof->result() as $row) {

			$get_mat = $this->db->query('SELECT dhm.materia_codigo as id from usuario u, docente_has_materia dhm, materia_has_pensum mhp, carrera c, pensum p where u.ci = dhm.usuario_ci and u.tipo=2 and mhp.pensum_id = p.id and mhp.materia_codigo = dhm.materia_codigo and p.carrera_id = c.id and c.id='.$this->carrera.' and mhp.semestre='.$this->semestre.' and u.ci="'.$row->ci.'"');
			$mat = $get_mat->result_array();

			$get_hour = $this->db->query('SELECT CONCAT(TIME_FORMAT(hora_inicio,"%h:%i %p"), " - ", TIME_FORMAT(hora_final,"%h:%i %p")) as hora, dia from view_horario where ci="'.$row->ci.'"');
			$ocup = $get_hour->result_array();

			$prof =array(
				'id'=>$row->ci,
				'nombre'=>$row->nombre." ".$row->apellido,
				'materia'=>$mat,
				//'hora'=>date("H:i:s",strtotime('08:00 PM')),
				'ocupado'=>$ocup
				);

			array_push($result['data'],$prof);

		}	


		return $result;

	}


	public function getCarreras( $id_Dpto ){

		$query = $this->db->query('SELECT * FROM carrera c where departamento_id = ? ', $id_Dpto);
		$data = $query->result_array();
		return $data;
	}

}
?>