<?php
/**
 * softn-cms
 */

namespace App\Rest\Searches;

use App\Rest\Common\Magic;

/**
 * @property bool $strict
 * Class BaseSearch
 * @author Nicolás Marulanda P.
 */
trait BaseSearch {
    
    use Magic;
    
    /**
     * @var bool
     */
    private $strict;
}
