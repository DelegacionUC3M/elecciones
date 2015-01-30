<?php

class adminController extends Controller {
	
	function index() {
		if ($this->security(false)) {
			$this->panel();
		} else {
			$preguntas = Pregunta::findAll();
			$this->render('inicio', array('preguntas' => $preguntas));
		}
	}

	//Función necesaria para que el caso de que la seguridad falle? exista el método panel de this.
	function panel() {

		echo "PEPEPEPEPEPEP";
		if(!$this->security(false)) header('Location: /debate/inicio');	
		$pregunta = new Pregunta();

		//Solo se quieren poder borrar preguntas, los if para like o introducir preguntas sobrarían.
		//Tampoco haría falta comprobar si es el dueño de la pregunta ya que el administrador debería 
		//poder borrar todo.
		if (isset($_POST['delete'])) {
			$pregunta = Pregunta::findById($_POST['pregunta_like']);
			$pregunta[0]->remove();
		}
		
		//Un array de 3 posiciones en las que cada posición es un array de preguntas de cada grupo.
		$arrayAlumnos = Pregunta::findByCategory('alumnos');
		$arrayPDI = Pregunta::findByCategory('pdi');
		$arrayPAS = Pregunta::findByCategory('pas');
		$this->render('admin', array('alumnos' => $arrayAlumnos,'pdi' => $arrayPDI,'pas' => $arrayPAS));
	}

	function preguntas() {
		$category = $_GET['type'];
		header('Content-Type: application/json');
		switch ($category) {
			case 'alumnos':
				echo json_encode(Pregunta::findByCategoryF('Alumnos'));
				break;
			case 'pdi':
				echo json_encode(Pregunta::findByCategoryF('Personal Docente e Investigador'));
				break;
			case 'pas':
				echo json_encode(Pregunta::findByCategoryF('Personal de Administracion y Servicios'));
				break;
			
			default:
				echo json_encode(array());
				break;
		}
	}
}