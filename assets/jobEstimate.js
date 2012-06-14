function onGarmentCostUpdate(costField, newCost, editable, estimate, total){
	var oldCost = $(costField).val() * 1;
	var garmentCount = getGarmentCount(estimate);
	var oldEstimate = $(estimate).val() * 1;
	var newEstimate = oldEstimate + 1 * newCost - oldCost;
	var editVal = $(editable).val() * 1;
	if(oldCost != newCost){		
		editVal = newEstimate;
		$(editable).val(editVal);
		refreshEstimate(editVal, newEstimate, $(estimate).parent());
		$(costField).val(newCost);
		$(total).val(editVal * garmentCount).change();		
	}
}

function updateLineTotal(calculatorUrl, editable, estimate, total, cost){
	var editVal = $(editable).val() * 1;
	var costVal = $(cost).val() * 1;
	var totalVal = 0;
	var garmentCount = getGarmentCount(estimate);
	var estimateVal = 0;
	calculateTotalCore(calculatorUrl, garmentCount, getFrontPasses(), getBackPasses(), getSleevePasses(), function(data){
		var oldEstimate = $(estimate).children('.hidden-price').val() * 1;
		editVal = 1 * data.result / garmentCount + costVal;
		$(editable).val(editVal);
		refreshEstimate(editVal, 1 * data.result / garmentCount + costVal, estimate);
		$(total).val(editVal * garmentCount).change();
	});
	$(total).val(editVal * garmentCount).change();
}