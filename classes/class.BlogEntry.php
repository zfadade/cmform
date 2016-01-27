<?php
class BlogEntry {
	public $postID, 
		$frTitle, $enTitle, 
		$frDesc, $enDesc, 
		$frContents, $enContents,
		$frPostDate, $enPostDate,
		$defaultLang;

	public function __construct() {
		$this->defaultLang = FRENCH;
	}

	public function getPostId() {
		return $this->postID;
	}

	public function setLang($lang)
	{
		$this->defaultLang = lang;
	}

	public function isPosted() {
		return $posted != 0;
	}

	public function getTitle($lang = NULL) {
		$language = is_null($lang) ? $this->defaultLang : $lang;
		return $language === ENGLISH ? $this->enTitle : $this->frTitle;
	}

	public function getTitles() {
		return $this->frTitle . '/' . $this->enTitle ;
	}
	
	public function setTitle($title, $lang) {
		if ($lang === ENGLISH) $this->enTitle = $title; else $this->frTitle = $title;
	}

	public function getDescription($lang) {
		return $lang === ENGLISH ? $this->enDesc : $this->frDesc;
	}


	public function setDescription($description, $lang) {
		if ($lang === ENGLISH) $this->enDesc = $description; else $this->frDesc = $description;
	}

	public function getContents($lang) {
		return $lang === ENGLISH ? $this->enContents : $this->frContents;
	}

	public function getPostDate($lang) {
		$date = $lang === ENGLISH ? $this->enPostDate : $this->frPostDate;
		return  empty($date) ? "" : strftime("%d %b %Y %H:%M", strtotime($date));
	}


	// public function isPosted($lang) {
	// 	return return $lang === ENGLISH ? $this->enDesc : $this->frDesc;
	// }
}
?>