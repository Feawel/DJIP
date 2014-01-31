<?php

namespace SdA\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SdAUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
