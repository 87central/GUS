<?php
class CalendarWidget extends CWidget {
	/**
	 * Name of the view which will be used to render each item contained in
	 * each day. When rendering, this view will be supplied with an $item variable
	 * containing the item to render.
	 */
	public $itemView;
	/**
	 * The data for all days in this widget. Valid days are Monday-Friday and Weekend.
	 * All days are optional, though omitting a day will not prevent it from being displayed.
	 * The data for each day shall be an array of items to display (key "items") and a 
	 * date value containing the date to render (key "date").
	 */
	public $data;
	/**
	 * True if draggable items can be dropped on days, otherwise false.
	 */
	public $droppable;
	/**
	 * The css class to be assigned to the container for each day.
	 */
	public $dayCss;
	/**
	 * The css class to be assigned to the wrapper of each item.
	 */
	public $itemCss;
	/**
	 * The css class to be assigned to the container for the current day. This
	 * will be assigned in addition to $dayCss
	 */
	 public $todayCss;
	 
	 
}