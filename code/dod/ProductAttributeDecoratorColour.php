<?php

class ProductAttributeDecoratorColour_Value extends DataObjectDecorator {

	/**
	 * default colour for font (fore colour) for displaying colours
	 * @var String
	 */
	protected static $default_colour = "000000";
		static function get_default_colour() {return self::$default_colour;}
		static function set_default_colour($s) {self::$default_colour = $s;}

	/**
	 * default background for displaying colours
	 * @var String
	 */
	protected static $default_contrast_colour = "FFFFFF";
		static function get_default_contrast_colour() {return self::$default_contrast_colour;}
		static function set_default_contrast_colour($s) {self::$default_contrast_colour = $s;}


	/**
	 * add styling to dropdowns option values?
	 * @var Boolean
	 */
	protected static $put_styling_in_dropdown_options = false;
		static function get_put_styling_in_dropdown_options() {return self::$put_styling_in_dropdown_options;}
		static function set_put_styling_in_dropdown_options($b) {self::$put_styling_in_dropdown_options = $b;}

	public function extraStatics() {
		return array (
			'db' => array(
				'RGBCode' => 'Varchar(6)',
				'ContrastRGBCode' => 'Varchar(6)'
			),
			'casting' => array(
				'ComputedRGBCode' => 'Varchar(6)',
				'ComputedContrastRGBCode' => 'Varchar(6)'
			)
		);
	}

	function updateCMSFields(FieldSet &$fields) {
		if($this->hasColour()) {
			$fields->addFieldToTab("Root.Colour", new TextField("RGBCode", "RGBCode"));
			$fields->addFieldToTab("Root.Colour", new TextField("ContrastRGBCode", "Contrast RGB Colour"));
		}
		else {
			$fields->removeFieldFromTab("Root.Main", "RGBCode");
			$fields->removeFieldFromTab("Root.Main", "ContrastRGBCode");
		}
	}

	function hasColour() {
		if($this->owner->Type()) {
			return $this->owner->Type()->IsColour;
		}
	}

	function ComputedRGBCode() {
		$colourCode = $this->owner->RGBCode;
		if(!$colourCode) {
			$colourCode = $this->IsHtmlColour($this->owner->Title);
			if(!$colourCode) {
				$colourCode = self::get_default_colour();
			}
		}
		return $colourCode;
	}

	function ComputedContrastRGBCode() {
		$colourCode = $this->owner->ContrastRGBCode;
		if(!$colourCode) {
			$colourCode = self::get_default_contrast_colour();
		}
		return $colourCode;
	}

	function updateValueForDropdown(&$v, $forceUpdate = false) {
		if(self::get_put_styling_in_dropdown_options()) {
			if($this->hasColour() || $forceUpdate) {
				$style = 'color: #'.$this->ComputedRGBCode().'; background-color: #'.$this->ComputedContrastRGBCode().';';
				$v = '<span style="'.$style.'">'.$v.'</span>';
			}
		}
		return $v;
	}

	/**
	 * it checks if the colourName exists and returns the colour code or an empty string if it does not exist
	 * "black" should return either #000000
	 * "blabla" should return ""
	 * @param String $colourName word for the colour (e.g. black)
	 * @return String
	 */
	protected function IsHtmlColour($colourName) {
		return strtolower($colourName);
	}
}

class ProductAttributeDecoratorColour_Type extends DataObjectDecorator {

	public function extraStatics() {
		return array (
			'db' => array(
				'IsColour' => 'Boolean'
			)
		);
	}

	function updateCMSFields(FieldSet &$fields) {
		$fields->addFieldToTab("Root.Colour", new CheckboxField("IsColour", _t("ProductAttributeDecoratorColour.ISCOLOUR", "Is Colour")));
	}

	function updateDropDownField(&$field){
		if(ProductAttributeDecoratorColour_Value::get_put_styling_in_dropdown_options()) {
			$newField = new ColourDropdownField(
				$name = $field->Name(),
				$title = $field->Title(),
				$source = $field->getSource(),
				$value = $field->Value(),
				null,
				$emptyString = $field->getEmptyString()
			);
			$field = $newField;
			return $field;
		}
	}
}
