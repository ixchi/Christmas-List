<?php

// An item in the wishlist

class Item extends Model {
	public function user() {
		return $this->belongs_to('User', 'user');
	}

	public function purchaser() {
		return $this->belongs_to('User', 'purchased');
	}
}
