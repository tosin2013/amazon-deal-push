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

namespace ApaiIO\Test\Operations;

use ApaiIO\Operations\Search;

class OperationsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testSearchException()
    {
        $search = new Search();
        $search->setPage(11);
    }    

    /**
     * @expectedException InvalidArgumentException
     */
    public function testMaximumPriceException()
    {
        $search = new Search();
        $search->setMaximumPrice(-1);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testMinimumPriceException()
    {
        $search = new Search();
        $search->setMinimumPrice('helloworld');
    }

    public function testSearchValidPage()
    {
        $search = new Search();
        $search->setPage(1);

        $this->assertEquals(1, $search->getItemPage());
    }

    public function testAbstractOperationSetterAndGetter()
    {
        $search = new Search();
        $search->setFoo('ABC');

        $this->assertEquals('ABC', $search->getFoo());
    }

    /**
     * @expectedException BadFunctionCallException
     */
    public function testAbstractOperationInvalidMethodName()
    {
        $search = new Search();
        $search->foo();
    }
}