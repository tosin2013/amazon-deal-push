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

namespace ApaiIO\Operations;

/**
 * A item search operation
 *
 * @see    http://docs.aws.amazon.com/AWSECommerceService/2011-08-01/DG/ItemSearch.html
 * @author Jan Eichhorn <exeu65@googlemail.com>
 */
class Search extends AbstractOperation
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ItemSearch';
    }

    /**
     * Sets the amazon category
     *
     * @param string $category
     *
     * @return \ApaiIO\Operations\Search
     */
    public function setCategory($category)
    {
        $this->parameter['SearchIndex'] = $category;

        return $this;
    }

    /**
     * Sets the keywords
     *
     * @param string $keywords
     *
     * @return \ApaiIO\Operations\Search
     */
    public function setKeywords($keywords)
    {
        $this->parameter['Keywords'] = $keywords;

        return $this;
    }

    /**
     * Sets the resultpage to a specified value
     * Allows to browse resultsets which have more than one page
     *
     * @param integer $page
     *
     * @return \ApaiIO\Operations\Search
     */
    public function setPage($page)
    {
        if (false === is_numeric($page) || $page < 1 || $page > 10) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%s is an invalid page value. It has to be numeric, positive and between 1 and 10',
                    $page
                )
            );
        }

        $this->parameter['ItemPage'] = $page;

        return $this;
    }

    /**
     * Sets the minimum price to a specified value for the search
     * Currency will be given by the site you are querying: EUR for IT, USD for COM
     * Price should be given as integer. 8.99$ USD becomes 899
     * 
     *
     * @param integer $price
     *
     * @return \ApaiIO\Operations\Search
     */
    public function setMinimumPrice($price)
    {
        if (false === is_numeric($price) || $price < 1) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%s is an invalid minimum price value. It has to be numeric and >= than 1',
                    $price
                )
            );
        }

        $this->parameter['MinimumPrice'] = $price;

        return $this;
    }
    
    /**
     * Sets the maximum price to a specified value for the search
     * Currency will be given by the site you are querying: EUR for IT, USD for COM
     * Price should be given as integer. 8.99$ USD becomes 899
     * 
     *
     * @param integer $price
     *
     * @return \ApaiIO\Operations\Search
     */    
    public function setMaximumPrice($price)
    {
        if (false === is_numeric($price)  || $price < 1) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%s is an invalid maximum price value. It has to be numeric and >= than 1',
                    $price
                )
            );
        }

        $this->parameter['MaximumPrice'] = $price;

        return $this;
    }
}
