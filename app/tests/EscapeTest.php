<?php
/**
 * EscapeTest.php
 */

namespace SoftnCMS\tests;

use SoftnCMS\controllers\Escape;

class EscapeTest extends \PHPUnit_Framework_TestCase {
    
    private $html;
    
    protected function setUp() {
        $this->html = '<h1>Encabezado</h1><p>Contenido</p>';
    }
    
    public function testHtmlEncode() {
        $output = Escape::htmlEncode($this->html);
        
        $this->assertEquals('&lt;h1&gt;Encabezado&lt;/h1&gt;&lt;p&gt;Contenido&lt;/p&gt;', $output);
        
        return $output;
    }
    
    /**
     * @depends testHtmlEncode
     */
    public function testHtmlDecode($htmlEncode) {
        $this->assertEquals($this->html, Escape::htmlDecode($htmlEncode));
    }
    
}
