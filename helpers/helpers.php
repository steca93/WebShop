<?php 
	function display_errors($errors){
		$display = '<ul class="bg-danger">';
		foreach ($errors as $error) {
			$display .= '<li class="text-danger">'.$error.'</li>';
		}
		$display .= '</ul>';
		return $display;
	}

	function sanitize($dirty){
		//FUNKCIJA KOJA NE DOZVOLJAVA DA SE UNOSE CUDNI STRINGOVI
		return htmlentities($dirty, ENT_QUOTES, "UTF-8");
	}

	function money($number){
		return '€ '.number_format($number,2);
	}
 ?>