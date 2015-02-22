<?php
/*
 * Copyright 2013 Jan Eichhorn <exeu65@googlemail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace ApaiIO\Test\Operations\Types;

use ApaiIO\Common\OperationTrait;
use ApaiIO\Operations\CartAdd;

class CardAddTest extends \PHPUnit_Framework_TestCase
{
    public function testSetter()
    {
        $cart = new CartAdd();
        $cart->setHMAC('1234');
        $cart->setCartId('789');
    }

    public function testGetName()
    {
        $cart = new CartAdd();
        $this->assertEquals('CartAdd', $cart->getName());
    }
}
