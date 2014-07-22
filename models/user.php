<?php

// A user

class User extends Model {
	public function items() {
		return $this->has_many('Item', 'user');
	}
}
