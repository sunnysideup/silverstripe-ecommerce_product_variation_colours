/*

=================== EXAMPLE ===================

jQuery(document).ready(
  function() {
		jQuery("select").sb({
			useTie: false,
			animDuration: 300,
			optionFormat: function() {
					if(jQuery(this).size() > 0) {
						var text = "";
						var label = jQuery(this).attr("label");
						if(label && label.length > 0) {
							text = label;
						}
						text = jQuery(this).text();
					}
					else {
						text = "";
					}
					var style = jQuery(this).attr("style");
					if(style){
						return "<span style=\""+style+"\">"+text+"</span>";
					}
					return text;
			},
			fixedWidth : false,
			arrowMarkup : '<span class="floatRight"><img src="/themes/main/images/scrollbar-arrow-down.png" alt="down arrow" /></span>',
		});
		jQuery(".has_sb option").each(
			function(i, el) {
				jQuery(el).html("hallo");
			}
		);
  }


);

 */
