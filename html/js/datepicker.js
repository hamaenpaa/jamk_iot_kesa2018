/* 
Last Mod: 19.7.2018 
Title: Datepicker global asetukset 
More info: https://xdsoft.net/jqplugins/datetimepicker/
----------
Asentaa kyseisen valikon classeihin nimeltä datetime_picker.
*/
$(function() {
	$.datetimepicker.setLocale('fi');
	jQuery('.datetime_picker').datetimepicker({ 
	datepicker:true,
	format:'d.m.Y H:i:s', //Finnish format
	});
});