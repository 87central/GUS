function calculateTotalCore(url, garments, front, back, sleeve, completion){
	$.getJSON(url,
	{
		garments: garments,
		front: front,
		back: back,
		sleeve: sleeve,
	},
	completion
	);
}

function calculateTotalMain(url, garments, front, back, sleeve, dest){
	calculateTotalCore(url, garments, front, back, sleeve, function(data){
		$(dest).val(data.result).change();
	});
}

function getFrontPasses(){
	return $('.front_pass').val();
}

function getBackPasses(){
	return $('.back_pass').val();
}

function getSleevePasses(){
	return $('.sleeve_pass').val();
}

function refreshEstimate(editVal, estimateVal, estimate){	
	$(estimate).children('span').html(estimateVal);
	$(estimate).children(':hidden').val(estimateVal);
	if(estimateVal == editVal){
		$(estimate).hide();
	} else {
		$(estimate).show();
	}
}

function updateLineTotal(calculatorUrl, editable, estimate, total){
	var editVal = $(editable).val() * 1;
	var totalVal = 0;
	var garmentCount = 0;
	var estimateVal = 0;
	$(estimate).parent().parent().children('.jobLine').children('.item_qty').each(function(){
		garmentCount += 1 * $(this).val();
	});
	calculateTotalCore(calculatorUrl, garmentCount, getFrontPasses(), getBackPasses(), getSleevePasses(), function(data){
		var oldEstimate = $(estimate).children(':hidden').val() * 1;
		if(oldEstimate == editVal){
			editVal = 1 * data.result / garmentCount
			$(editable).val(editVal);
		}
		refreshEstimate(editVal, 1 * data.result / garmentCount, estimate);
		$(total).val(editVal * garmentCount).change();
	});
	$(total).val(editVal * garmentCount).change();
}

function recalculateTotal(editable, estimate, total){
	var editVal = $(editable).val() * 1;
	var garmentCount = 0;
	$(estimate).parent().parent().children('.jobLine').children('.item_qty').each(function(){
		garmentCount += 1 * $(this).val();
	});
	refreshEstimate(editVal, $(estimate).children(':hidden').val(), estimate);
	$(total).val(editVal * garmentCount).change();
}