<h1>Cost Estimator</h1>

<?php echo $this->renderPartial('_estimate', array(
	'model'=>$model,
	'print'=>$print,
	'passes'=>$passes,
	'lineData'=>$lineData,
)); ?>

<p>Insert disclaimer about accuracy of estimate here.</p>