function getName(garmentCount, item){
	return 'garments[' + garmentCount + '][' + item + ']';
}

function getFieldId(garmentCount, item){
	return 'garments_' + garmentCount + '_' + item;
}

function generateInput(name, id, value, type, readonly){
	var retVal = "<input type=\"" + type + "\"value=\"" + value + "\" id=\"" + id + "\" name=\"" + name + "\"";
	if(readonly){
		retVal = retVal + " readonly=\"readonly\"";
	}
	retVal = retVal + "/>";
	return retVal;
}

function addGarment(style, color, size, file, passes, quantity){
	var garmentCount = $('#garment_count').val();
	var styleName = getName(garmentCount, 'style');
	var colorName = getName(garmentCount, 'color');
	var sizeName = getName(garmentCount, 'size');
	var fileName = getName(garmentCount, 'file');
	var passName = getName(garmentCount, 'passes');
	var qtyName = getName(garmentCount, 'quantity');
	
	var styleID = getFieldId(garmentCount, 'style');
	var colorID = getFieldId(garmentCount, 'color');
	var sizeID = getFieldId(garmentCount, 'size');
	var fileID = getFieldId(garmentCount, 'file');
	var passID = getFieldId(garmentCount, 'passes');
	var qtyID = getFieldId(garmentCount, 'quantity');
	
	var style = generateInput(styleName, styleID, $('#garment_style').val(), 'hidden', false);
	var color = generateInput(colorName, colorID, $('#garment_color').val(), 'hidden', false);
	var size = generateInput(sizeName, sizeID, $('#garment_size').val(), 'hidden', false);
	var file = generateInput(fileName, fileID, $('#garment_file').val(), 'hidden', false);
	var pass = generateInput(passName, passID, $('#garment_passes').val(), 'hidden', false);
	var qty = generateInput(qtyName, qtyID, $('#garment_quantity').val(), 'hidden', false);
	
	$('#lines').prepend(style).prepend(color).prepend(size).prepend(file).prepend(pass).prepend(qty);
	$('#garment_count').val(garmentCount * 1 + 1);
	
}



function removeGarment(){
	
}