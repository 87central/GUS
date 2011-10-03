<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<!--<div id="header">-->
	<div class="sidemenu">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
		<!--</div>--><!-- header -->
	
		<div id="mainmenu">
			<?php $isAdmin = true; //should be more complex?>
			<?php $isCustomer = false;?>
			<?php if(Yii::app()->user->isGuest){?>
				<?php $this->widget('zii.widgets.CMenu',array(
					'items'=>array(
						array('label'=>'Home', 'url'=>array('/site/index')),
						array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
						array('label'=>'Contact', 'url'=>array('/site/contact')),
						array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
						array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
					),
					'lastItemCssClass'=>'lastmenu',
				)); ?>
			<?php } else {?>
				<?php $this->widget('zii.widgets.CMenu', array(
					'items'=>array(
						array('label'=>'My Jobs', 'url'=>array('/job/dashboard')),
						array('label'=>'New Job', 'url'=>array('/job/create')),
						array('label'=>'All Jobs', 'url'=>array('/job/index')),
						array('label'=>'Check In', 'url'=>array('/order/checkin')),
						array('label'=>'Calendar', 'url'=>array('/event/schedule')),
					),
					'lastItemCssClass'=>'lastmenu',
				)); ?>
			<?php }?>
		</div><!-- mainmenu -->
	</div>
	
	<?php if(!Yii::app()->user->isGuest){?>
		Welcome <?php echo Yii::app()->user->name;?>&nbsp;
		<span class="note"><?php echo date('l F j');?></span>
		<br/>
		<div class="messages">
			<strong>Important Messages...</strong>
		</div>
		<br/>
	<?php }?>

	<?php echo $content; ?>

</div><!-- page -->

</body>
</html>