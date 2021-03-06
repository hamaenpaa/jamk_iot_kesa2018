/* 
Last Mod: 24.7.2018 
Title: Datepicker global asetukset 
More info: https://xdsoft.net/jqplugins/datetimepicker/
----------
Asentaa kyseisen valikon classeihin nimeltä datetime_picker.
*/
$(function() {
	$.datetimepicker.setLocale('fi');
	setUpDatetimepickers();
});

function setUpDatetimepickers() {
	jQuery('.datetime_picker').datetimepicker({ 
		datepicker:true,
		allowTimes:[
			'06:00', '06:15', '06:30', '06:45', //6
			'07:00', '07:15', '07:30', '07:45', //7
			'08:00', '08:15', '08:30', '08:45', //8
			'09:00', '09:15', '09:30', '09:45', //9
			'10:00', '10:15', '10:30', '10:45', //10
			'11:00', '11:15', '11:30', '11:45', //11
			'12:00', '12:15', '12:30', '12:45', //12
			'13:00', '13:15', '13:30', '13:45',
			'14:00', '14:15', '14:30', '14:45',
			'15:00', '15:15', '15:30', '15:45',
			'16:00', '16:15', '16:30', '16:45',
			'17:00', '17:15', '17:30', '17:45',
			'18:00', '18:15', '18:30', '18:45',
			'19:00', '19:15', '19:30', '19:45',
			'20:00', '20:15', '20:30', '20:45',  //20
			'21:00'
		],
		format:'d.m.Y H:i' //Finnish format
	});	
}

$(function() {
	$.datetimepicker.setLocale('fi');
	jQuery('.date_picker').datetimepicker({
		datepicker:true,
		timepicker:false,
		format:'d.m.Y' //Finnish format
	});
});

$(function() {
	$.datetimepicker.setLocale('fi');
	jQuery('.time_picker').datetimepicker({
		datepicker:false,
		timepicker:true,
		format:'H:i', //Finnish format
		allowTimes:[
			'06:00', '06:15', '06:30', '06:45', //6
			'07:00', '07:15', '07:30', '07:45', //7
			'08:00', '08:15', '08:30', '08:45', //8
			'09:00', '09:15', '09:30', '09:45', //9
			'10:00', '10:15', '10:30', '10:45', //10
			'11:00', '11:15', '11:30', '11:45', //11
			'12:00', '12:15', '12:30', '12:45', //12
			'13:00', '13:15', '13:30', '13:45',
			'14:00', '14:15', '14:30', '14:45',
			'15:00', '15:15', '15:30', '15:45',
			'16:00', '16:15', '16:30', '16:45',
			'17:00', '17:15', '17:30', '17:45',
			'18:00', '18:15', '18:30', '18:45',
			'19:00', '19:15', '19:30', '19:45',
			'20:00', '20:15', '20:30', '20:45', //20
			'21:00'		
		]
	});	
});