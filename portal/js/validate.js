function Checkfiles()
{
    var file = $("#image")[0].files[0];

    var fileName = (file.name);
    var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
    var imgbytes = file.size;

    var imgkbytes = Math.round(parseInt(imgbytes) / 1024);

    if (imgkbytes > 200) {

        return false;
    }
    if (ext == "jpg" || ext == "png" || ext == "jpeg")
    {
        return true;
    }
    else
    {
        return false;
    }
}


function checkImage(tmpfile)
{
   var file = $("#"+tmpfile)[0].files[0];
    var fileName = (file.name);
    var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
    var imgbytes = file.size;

    var imgkbytes = Math.round(parseInt(imgbytes) / 1024);

    if (imgkbytes > 200) {

        return false;
    }
    if (ext == "jpg" || ext == "jpeg" || ext == "png")
    {
        return true;
    }
    else
    {
        return false;
    }
}

//Only numbers 0-9
function validateNumber(event) {
    var key = window.event ? event.keyCode : event.which;
    if (event.keyCode === 8 || event.keyCode === 46) {
        return true;
    } else if ( key < 48 || key > 57 ) {
        return false;
    } else {
        
	return true;
    }
}


//Only numbers 0-9 and a dot
function validateDecimalNumber(txt, event) {
    var key = window.event ? event.keyCode : event.which;
    if (event.keyCode === 8 || event.keyCode === 46) {
        return true;
    } else if (key == 46){
	if (txt.value.indexOf('.') === -1) {
            return true;
        } else {
            return false;
        }		
    } else if ( key < 48 || key > 57 ) {
        return false;
    } else {
	
        return true;
    }
}

//Only numbers 0-9 and - sign
function validateNegativeNumber(txt, event) {
    var key = window.event ? event.keyCode : event.which;
    if (event.keyCode === 8 || event.keyCode === 46) {
        return true;
    } else if (key == 45){	
	if (txt.value.indexOf('-') === -1) {
            return true;
        } else {
            return false;
        }
    } else if ( key < 48 || key > 57 ) {
        return false;
    } else {
        return true;
    }
}


//phone nos
function isNumberKey(evt)
{
//    var mobile=/^\+(?:[0-9] ?){6,14}[0-9]$/;
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode != 8) && (charCode != 127))
        return false;
    return true;
}

function alertClose(input)
{
    $(input).parent().remove();
}



//RI
// JavaScript Document
function confirm_delete(){
	
	var r = confirm("Are you sure!");
	if (r == true) {
		return true;
	} else {
		return false;
	}
}

//Hexadecimal to Decimal
function hexToDec(hexNum){
    var dec = parseInt(hexNum, 16);
    
    return dec;
}

//Decimal to Hexadecimal        
function decToHex(dec){
    var hex = dec.toString(16); 
    
    return hex;
}

$(document).keypress(
    function(event){
     if (event.which == '13') {

        event.preventDefault();
      }
});