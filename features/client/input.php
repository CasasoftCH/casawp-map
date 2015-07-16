<?php 
	namespace casasoft\casasyncmap;

	class Input{
		private $name = "";
		private $type = "";
		private $value = "";
		private $id = "";

		private $default_args = array(
			"label" 	=> "",
			"id"		=> "",
			"checked" => false
		);

		protected $label;
		public function getLabel(){return $this->label;}
		public function setLabel($label){$this->label = $label;}
		public function retrieveLabel(){
			switch ($this->type) {
				case 'checkbox':
					return ($this->args['label'] ? $this->args['label'] : $this->name);
					break;
			}
			return $this->label;
		}

		/*
		@$args = array(
			"label" 	=> "",
			"id"		=> "",
			"checked" => false
		);
		*/
		function __construct($type, $name, $value, $args = array()){
			$this->type = $type;
			$this->name = $name;
			$this->value = $value;
			$this->args = array_merge($this->default_args, $args);
		}
		function __toString(){
			$html = "";
			switch ($this->type) {
				case 'checkbox':
					$html .= "<div class='term-checkbox " . (($this->value) ? $this->value : "") . "'>";
					$html .= "<label>";
					$html .= "<input type='checkbox' name='" . $this->name . "' value='" . $this->value . "' " . ($this->args["checked"] ? "CHECKED" : "") . "> ";
					$html .= $this->retrieveLabel();
					$html .= "</label>";
					$html .= "</div>";
					break;
				default:

					break;
			}

			return $html;
		}
	}