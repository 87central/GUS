<?php /*render inventory item lines (products and services), then render transaction lines associated with those items.*/?>
<?php 
$tab = chr(9);
$newLine = chr(13) . chr(10);
function renderRecord($record, $separator=null){
	if($separator === null){
		$separator = chr(9);
	}
	foreach($record as $fieldValue){
		echo $fieldValue . $separator;
	}
	echo chr(13) . chr(10);
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

echo 'ENDTRNS' . $newLine;
?>