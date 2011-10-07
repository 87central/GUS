<?php $this->widget('application.widgets.CalendarWidget', array(
	'droppable'=>true,
	'sortable'=>true,
	'itemCss'=>'calendar_item',
	'dayCss'=>'sortable',
	'itemView'=>'//job/_eventDetail',
	'headerView'=>'//job/_dayHeader',
	'data'=>$calendarData,
));?>