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
	<!--[if lte IE 7]>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery.dropdown.js"></script>
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
			<?php $isAdmin = Yii::app()->user->getState('isAdmin');//Yii::app()->user->isAdmin; //should be more complex?>
			<?php $isCustomer = Yii::app()->user->getState('isCustomer');//Yii::app()->user->isCustomer;?>
			<?php if(Yii::app()->user->isGuest){?>
				<?php $this->widget('application.extensions.emenu.EMenu',array(
					'items'=>array(
						array('label'=>'Home', 'url'=>array('/site/index')),
						array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
						array('label'=>'Contact', 'url'=>array('/site/contact')),
						array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
						array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
					),
					'themeCssFile'=>$this->styleDirectory . 'dropdown/default.css',
					'lastItemCssClass'=>'lastmenu',
					'vertical'=>true,
				)); ?>
			<?php } else {?>
				<?php $this->widget('application.extensions.emenu.EMenu', array(
					'items'=>array(
						array('label'=>'My Jobs', 'url'=>array('/job/index')),
						array('label'=>'New Job', 'url'=>array('/job/create')),
						array('label'=>'All Jobs', 'url'=>array('/job/list')),
						//array('label'=>'Check In', 'url'=>array('/order/index'), 'visible'=>$isAdmin),
						//array('label'=>'Create Order', 'url'=>array('/order/create')),
						array('label'=>'New Invoice', 'url'=>array('/invoice/create')),
						array('label'=>'All Invoices', 'url'=>array('/invoice/index')),						
						array('label'=>'Calendar', 'url'=>array('/event/schedule')),
						array('label'=>'Logout', 'url'=>array('/site/logout')),
					),
					'themeCssFile'=>$this->styleDirectory . 'dropdown/default.css',
					'lastItemCssClass'=>'lastmenu',
					'vertical'=>true,
				)); ?>
				<?php 
					if($isAdmin){
						$this->widget('application.extensions.emenu.EMenu', array(
							'items'=>array(
								array('label'=>'Add Product', 'url'=>array('/product/create')),
								array('label'=>'Add Vendor', 'url'=>array('/vendor/create')),
								array('label'=>'Add User', 'url'=>array('/user/create')),
								array('label'=>'View Products', 'url'=>array('/product/index'), 'items'=>$this->products),
								array('label'=>'View Vendors', 'url'=>array('/vendor/index')),
								array('label'=>'View Customers', 'url'=>array('/customer/index')),
								array('label'=>'View Users', 'url'=>array('/user/index')),
								array('label'=>'Colors, Etc.', 'url'=>array('/lookup/index', 'Color'=>1, 'Style'=>1, 'Size'=>1))
							),
							'themeCssFile'=>$this->styleDirectory . 'dropdown/default.css',
							'lastItemCssClass'=>'lastmenu',
							'vertical'=>true,
						));
					}
				?>
			<?php }?>
		</div><!-- mainmenu -->
	</div>
	
	<?php if(!Yii::app()->user->isGuest){?>
		<div class="bonnet">
			<span class="greeting">Welcome <?php echo Yii::app()->user->name;?></span>&nbsp;
			<span class="note"><?php echo date('l F j');?></span>
			<br/>
			<!--<div class="messages">
				<?php foreach($this->messages as $message){?>
					<strong><?php echo $message;?></strong>
					<br/>
				<?php }?>
			</div>-->
			<br/>
		</div>
	<?php }?>
	
	<?php if(Yii::app()->user->hasFlash('success')){?>
		<div class="flash-success">
			<?php echo Yii::app()->user->getFlash('success');?>
		</div>
	<?php } else if(Yii::app()->user->hasFlash('failure')){?>
		<div class="flash-error">
			<?php echo Yii::app()->user->getFlash('failure');?>
		</div>
	<?php }?>

	<?php echo $content; ?>

</div><!-- page -->

</body>
</html>