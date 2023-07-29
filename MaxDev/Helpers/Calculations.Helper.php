<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>.
 * @Copyright Maximum Develop
 * @FileCreated: 10/28/18 4:05 PM
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

function Percentage($value, $total)
{
    if ($value == 0) {
        return 0;
    }
    return ($value * 100) / $total;
}
