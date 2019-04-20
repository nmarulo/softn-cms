<?php
/**
 * softn-cms
 */

namespace App\Rest\Common;

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
