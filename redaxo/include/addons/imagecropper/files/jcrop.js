//JCrop starten
jQuery(document).ready(function(){
	//Crop erzeugen
	var api = jQuery.Jcrop("#cropImage",{
		aspectRatio: 0,
		boxWidth: 600,
		boxHeight: 500,
		onSelect: icrop_getCoords,
		onChange: icrop_getCoords
	});
		icrop_checkRatio();	//Starteinstellung holen
		api.release(); //Rahmen beim Start zurücksetzen
	
	var i, ac;
	
	function nothing(e)
	{	e.stopPropagation();
		e.preventDefault();
		return false;
	}	
	function anim_handler(preset)
	{	return function(e) {
			var coords = jQuery('#' + preset).val();
				coords = coords.split("x");
				
			var x1 = parseInt(jQuery('#icrop_x1').val());
				if (x1 == "NaN") {
					x1 = 0;
				}
			var y1 = parseInt(jQuery('#icrop_y1').val());
				if (y1 == "NaN") {
					y1 = 0;
				}
			//Endwerte neu berechnen
			x2 = x1 + parseInt(coords[0]);
			y2 = y1 + parseInt(coords[1]);
				
			api.animateTo([x1,y1,x2,y2]);
			return nothing(e);
		}
	}
	
	function icrop_checkRatio()
	{	obj = jQuery('#icrop_seitenverh');
			objstat = obj.attr("checked");
			
			api.setOptions(objstat ? { aspectRatio: 3/2 } : { aspectRatio: 0 });
			api.focus();
	}
	
	var presets = new Array("icrop_preset1", "icrop_preset2", "icrop_preset3", "icrop_preset4", "icrop_preset5");
		for (k=0; k<presets.length; k++)
		{	jQuery('#' + presets[k]).click(anim_handler(presets[k]));
		}
		
		
	if (1 == 1){
		jQuery('#icrop_seitenverh').attr('checked', true);
	} else {
		jQuery('#icrop_seitenverh').attr('checked', false);
	}
	
	jQuery('#icrop_seitenverh').change(function(e) {
		api.setOptions(this.checked ? { aspectRatio: 3/2 } : { aspectRatio: 0 });
		api.focus();
	});
	
	jQuery('#icrop_release').click(function(e) {
		jQuery('#icrop_x1, #icrop_x2, #icrop_y1, #icrop_y2, #icrop_width, #icrop_height').val('');
		api.release();
		showInfo();
	});

});

	function showInfo()
	{	var w = parseInt(jQuery('#icrop_width').val());
		var h = parseInt(jQuery('#icrop_height').val());
		var t = jQuery('#icropselection');
			if ((w == "" && h == "") || (isNaN(w) && isNaN(h)) || (w == 0 && h == 0)) {
				t.text("");
			} else {
				t.text("Auswahl B/H: " +w+ "/" +h);
			}
	}

function icrop_getCoords(c)
{	jQuery('#icrop_x1').val(c.x);
	jQuery('#icrop_x2').val(c.x2);
	jQuery('#icrop_y1').val(c.y);
	jQuery('#icrop_y2').val(c.y2);
	jQuery('#icrop_width').val(c.w);
	jQuery('#icrop_height').val(c.h);
	showInfo();
	//jQuery('#icropselection').text("Auswahl B/H: " + c.w + "/" + c.h);
}

function icrop_checkform()
{	if (jQuery('#icrop_imgname').val() == '') {
		alert(icrop_Submit);
		return false;
	} else {
		return true;
	}
}