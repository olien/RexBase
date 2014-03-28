<?php

class seo42_tool {
	protected $title;
	protected $description;
	protected $link;

	public function __construct($title, $description, $link) {
		$this->title = $title;
		$this->description = $description;
		$this->link = $link;
	} 

	public function getTitle() {
		return $this->title;
	} 

	public function getDescription() {
		return $this->description;
	} 

	public function getLink() {
		return $this->link;
	} 
}
