<?php /*render inventory item lines (products and services), then render transaction lines associated with those items.*/?>
<?php 
function renderRecord($record, $separator='\t'){
	foreach($record as $fieldValue){
		echo $record . $separator;
	}
	echo '\n';
}

foreach ($model->jobLines as $line) {
	foreach($line->inventoryLines->records as $record){
		renderRecord($record);
	}
}

foreach($model->inventoryLines->records as $record){
	renderRecord($record);
}

renderRecord($model->transaction->record);

foreach($model->jobLines as $line){
	foreach($line->transactionLines->records as $record){
		renderRecord($record);
	}
}

foreach($model->transactionLines->records as $record){
	renderRecord($record);
}

echo 'ENDTRNS\n';
?>