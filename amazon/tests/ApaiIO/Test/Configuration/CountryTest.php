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

namespace ApaiIO\Test\Configuration;

use ApaiIO\Configuration\Country;

class CountryTest extends \PHPUnit_Framework_TestCase
{
    public function testCountryList()
    {
        $this->assertEquals(
            array('de', 'com', 'co.uk', 'ca', 'fr', 'co.jp', 'it', 'cn', 'es', 'in'),
            Country::getCountries()
        );
    }

    public function testUnvalidCountryWithoutException()
    {
        $this->assertFalse(Country::isValidCountry(__METHOD__, false));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUnvalidCountryWithExcetion()
    {
        Country::isValidCountry(__METHOD__);
    }

    public function testValidCountry()
    {
        $this->assertTrue(Country::isValidCountry('com'));
    }
}
