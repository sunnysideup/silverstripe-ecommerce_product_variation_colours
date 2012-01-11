<?php
/**
 * extension of the dropdown field specifically for colour display in dropdown
 *
 */
class ColourDropdownField extends DropdownField {


	/**
	 * Location used for Javascript that transforms dropdown
	 * (you can set alternative location)
	 *
	 * @var String
	 *
	 *
	 */
	protected static $js_location_for_select_styling = "ecommerce_product_variation_colours/javascript/ColourDropdownField.js";
		static function get_js_location_for_select_styling() {return self::$js_location_for_select_styling;}
		static function set_js_location_for_select_styling($s) {self::$js_location_for_select_styling = $b;}

	/**
	 * The ONLY difference we are making here is to remove the
	 * "Convert::raw2xml($title)" and replace it with "$title"
	 */
	function Field() {
		Requirements::javascript(self::get_js_location_for_select_styling());
		Requirements::themedCSS("ColourDropdownField");
		$domd = new DOMDocument();
		libxml_use_internal_errors(true);
		libxml_use_internal_errors(false);
		$options = '';

		$source = $this->getSource();
		if($source) {
			// For SQLMap sources, the empty string needs to be added specially
			if(is_object($source) && $this->emptyString) {
				$options .= $this->createTag('option', array('value' => ''), $this->emptyString);
			}

			foreach($source as $value => $title) {

				// Blank value of field and source (e.g. "" => "(Any)")
				if($value === '' && ($this->value === '' || $this->value === null)) {
					$selected = 'selected';
				}
				else {
					// Normal value from the source
					if($value) {
						$selected = ($value == $this->value) ? 'selected' : null;
					}
					else {
						// Do a type check comparison, we might have an array key of 0
						$selected = ($value === $this->value) ? 'selected' : null;
					}

					$this->isSelected = ($selected) ? true : false;
				}
				$style = "";
				if($title) {
					$domd->loadHTML($title);
					$domx = new DOMXPath($domd);
					$items = $domx->query("//span[@style]");
					$style = "";
					foreach($items as $item) {
						foreach ($item->attributes as $attrName => $attrNode) {
							if($attrName == "style")
								$style = $attrNode->nodeValue;
						}
					}
					$options .= $this->createTag(
						'option',
						array(
							'selected' => $selected,
							'value' => $value,
							'style' => $style,
							'rel' => $style
						),
						$this->createTag(
							'span',
							array(
								'style' => $style,
							),
							strip_tags($title)
						)
					);
				}
			}
		}

		$attributes = array(
			'class' => ($this->extraClass() ? $this->extraClass() : ''),
			'id' => $this->id(),
			'name' => $this->name,
			'tabindex' => $this->getTabIndex()
		);

		if($this->disabled) $attributes['disabled'] = 'disabled';
		return $this->createTag('select', $attributes, $options);
	}

}
