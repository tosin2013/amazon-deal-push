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

namespace ApaiIO\Test\ResponseTransformer;

use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\ResponseTransformer\ObjectToArray;
use ApaiIO\ResponseTransformer\ResponseTransformerFactory;

class BaseFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testValidResponseObjectFromString()
    {
        $conf = new GenericConfiguration();
        $conf->setResponseTransformer('\ApaiIO\ResponseTransformer\ObjectToArray');

        $Response = ResponseTransformerFactory::createResponseTransformer($conf);

        $this->assertInstanceOf('\ApaiIO\ResponseTransformer\ObjectToArray', $Response);
    }

    public function testValidResponseObjectFromObject()
    {
        $conf = new GenericConfiguration();
        $conf->setResponseTransformer(new ObjectToArray());

        $Response = ResponseTransformerFactory::createResponseTransformer($conf);

        $this->assertInstanceOf('\ApaiIO\ResponseTransformer\ObjectToArray', $Response);
    }

    /**
     * @expectedException LogicException
     */
    public function testInvalidResponseObjectFromString()
    {
        $conf = new GenericConfiguration();
        $conf->setResponseTransformer('\Exception');

        $Response = ResponseTransformerFactory::createResponseTransformer($conf);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNonExistingResponseObjectFromString()
    {
        $conf = new GenericConfiguration();
        $conf->setResponseTransformer('\XFOO');

        $Response = ResponseTransformerFactory::createResponseTransformer($conf);
    }

    /**
     * @expectedException LogicException
     */
    public function testInvalidResponseObjectFromObject()
    {
        $conf = new GenericConfiguration();
        $conf->setResponseTransformer(new \Exception());

        $Response = ResponseTransformerFactory::createResponseTransformer($conf);
    }

    public function testSameResponse()
    {
        $conf = new GenericConfiguration();
        $conf->setResponseTransformer('\ApaiIO\ResponseTransformer\ObjectToArray');

        $ResponseA = ResponseTransformerFactory::createResponseTransformer($conf);
        $ResponseB = ResponseTransformerFactory::createResponseTransformer($conf);

        $this->assertSame($ResponseA, $ResponseB);
    }

    public function testFactoryCallback()
    {
        $that = $this;
        $conf = new GenericConfiguration();
        $conf->setResponseTransformer('\ApaiIO\ResponseTransformer\XmlToDomDocument');
        $conf->setResponseTransformerFactory(
            function ($response) use ($that) {
                $that->assertInstanceOf('\ApaiIO\ResponseTransformer\XmlToDomDocument', $response);
                return $response;
            }
        );

        ResponseTransformerFactory::createResponseTransformer($conf);
    }

    /**
     * @expectedException LogicException
     */
    public function testInvalidRequestFactoryCallbackReturnValue()
    {
        $conf = new GenericConfiguration();
        $conf->setResponseTransformer('\ApaiIO\ResponseTransformer\XmlToDomDocument');
        $conf->setResponseTransformerFactory(
            function ($response) {
                return new \stdClass();
            }
        );

        ResponseTransformerFactory::createResponseTransformer($conf);
    }
}
