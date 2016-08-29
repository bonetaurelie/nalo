<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 29/08/16
 * Time: 21:47
 */

namespace Tests\UserBundle\Model;


use UserBundle\Entity\User;

class TestUser extends User
{
	public function setId($id)
	{
		$this->id = $id;
	}
}