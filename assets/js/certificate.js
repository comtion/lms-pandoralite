// Select option
$(function() {
	$('#certimg').change(function(){
		$('.type').hide();
		$('#' + $(this).val()).show();
	});
});

//check upload file
var _validFileExtensions = [".jpg"];    
function ValidateSingleInput(oInput) {
	if (oInput.type == "file") {
		var sFileName = oInput.value;
		if (sFileName.length > 0) {
			var blnValid = false;
			for (var j = 0; j < _validFileExtensions.length; j++) {
				var sCurExtension = _validFileExtensions[j];
				if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
					blnValid = true;
					break;
				}
			}

			if (!blnValid) {
				alert("This file can not be uploaded. Please select a .jpg extension.");
				oInput.value = "";
				return false;
			}
		}
	}
	return true;
}