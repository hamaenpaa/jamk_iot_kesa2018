<?php
	include("inc/sub/setting/fetch_settings_from_db.php");
    $index_page = "index.php?screen=5";
	$settings = getSettings($conn);
?>	
<h2 id="add_or_modify_settings_header">Muokkaa asetuksia</h2>
<form id="modify_settings" name="modify_settings" action="inc/sub/setting/save_settings.php" method="POST" >	
<div class="row-type-5">
	Oletus huoneen tunnus
	(käytetään, kun yksikäsitteistä oppituntia ja 
	sen tunnusta ei löydetä huonelokissa):<br>
	<input id="default_roomidentifier" name="default_roomidentifier"  
		autocomplete="off" 
		value="<?php echo $settings['default_roomidentifier']; ?>" />		
</div>
<div class="row-type-5">
	<label>Käyttökohteen tyyppi:</label>
	<select id="usage_type" name="usage_type" autocomplete="off" 
		value="<?php echo $settings['usage_type']; ?>" >
		<option <?php if ($settings['usage_type'] == 1) { echo " selected"; } ?> value="1" >
			Dynamit/Dynamo
		</option>
		<option <?php if ($settings['usage_type'] == 2) { echo " selected"; } ?> value="2" >
			EXPA
		</option>
	</select>
</div>
<div class="row-type-5">
	<label>Sivukoko:</label>
	<input id="page_size" name="page_size"  
		autocomplete="off" 
		value="<?php echo $settings['page_size']; ?>" />
</div>
<div class="row-type-5">
	<label>Sivuja yhdessä sivukokoelmassa:</label>
	<input id="page_page_size" name="page_page_size"  
		autocomplete="off" 
		value="<?php echo $settings['page_page_size']; ?>" />
</div>

<div class="row-type-5">
	<input class="button" type="submit" 
		onsubmit="return checkSetting();" value="Talleta"/>
</div>
<div id="add_or_modify_lesson_form_validation_errors"></div>	