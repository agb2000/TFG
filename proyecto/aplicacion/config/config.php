<?php
	$config=array("CONTROLADOR"=> array("inicial"),
				"RUTAS_INCLUDE"=>array("aplicacion/modelos"), // La autocarga de clases
				"URL_AMIGABLES"=>true, // Para que tengamos url entendibles comparadas con las de siempre
				"VARIABLES"=>array("autor"=>"Alberto Godoy Borrego daw",
				  	"direccion"=>"C/ CMNO Fuente Mora Nº11"), // Variables que se puede usar en cualquier punto de la aplicacion
				"BD"=>array("hay"=>true, // Base de Datos no iniciada
							"servidor"=>"localhost",
							"usuario"=>"proyecto_final",
							"contra"=>"proyecto_final",
							"basedatos"=>"proyecto_final"), // Acceso a la Base de Datos
				"SESION" => array("controlAutomatico" => true),
				"ACL" => array("controlAutomatico" => true)
	);

