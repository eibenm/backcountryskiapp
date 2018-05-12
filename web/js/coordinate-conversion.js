//Coverts Degrees Minutes Seconds coordinates to Decimal Degrees
function convertDMS(event)
{
	var deg = parseFloat(document.getElementById('dDmsLat').value);
	var min = parseFloat(document.getElementById('mDmsLat').value);
	var sec = parseFloat(document.getElementById('sDmsLat').value);
	
	var degLong = parseFloat(document.getElementById('dDmsLong').value);
	var minLong = parseFloat(document.getElementById('mDmsLong').value);
	var secLong = parseFloat(document.getElementById('sDmsLong').value);

	var coord = 0;
	var coordLong = 0;

	coord = deg + (min + sec/60)/60;
	coordLong = degLong - (minLong + secLong/60)/60;
	
	document.getElementById('gps-lat').value = Number((coord).toFixed(6));
	document.getElementById('gps-lon').value = Number((coordLong).toFixed(6));

	event.preventDefault();
}
	
function convertDDM(event)
{
	var deg = parseFloat(document.getElementById('d_ddm').value);
	var min = parseFloat(document.getElementById('m_ddm').value);
	
	var degLong = parseFloat(document.getElementById('dl_ddm').value);
	var minLong = parseFloat(document.getElementById('ml_ddm').value);
	
	var coord = 0;
	var coordLong = 0;

	coord = deg + min/60;
	coordLong = degLong - minLong/60;
	
	document.getElementById('gps-lat').value = Number((coord).toFixed(6));
	document.getElementById('gps-lon').value = Number((coordLong).toFixed(6));

	event.preventDefault();
}