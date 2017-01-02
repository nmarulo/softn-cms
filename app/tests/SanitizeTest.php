<?php
/**
 * SanitizeTest.php
 */

namespace SoftnCMS\tests;

use SoftnCMS\controllers\Sanitize;

class SanitizeTest extends \PHPUnit_Framework_TestCase {
    
    public function testAlphanumeric() {
        $this->assertEquals('', Sanitize::alphanumeric(''));
        $this->assertEquals('a-2-c-3-é-f', Sanitize::alphanumeric('  a  2 c 3 é     f   ', TRUE, TRUE));
        $this->assertEquals('abcéí123', Sanitize::alphanumeric('a<%><¡/\|*:.;,!-+>[]{}bcé#í123+'));
        $this->assertEquals('abc123', Sanitize::alphanumeric('abcé#í123+', FALSE));
        $this->assertEquals('a_b_c_1_2_3', Sanitize::alphanumeric('a <><¡/\|*:.;,!-+>[]{}b c 1 2 3+', TRUE, TRUE, '_'));
    }
    
    public function testClearTags() {
        $this->assertEquals('', Sanitize::clearTags('<p></p><script></script><iframe></iframe><?php echo "a"; ?>'));
    }
    
    public function testAlphabetic() {
        $this->assertEquals('', Sanitize::alphabetic(''));
        $this->assertEquals('áéíóúÁÉÍÓÚ', Sanitize::alphabetic('áéíóúÁÉÍÓÚ'));
        $this->assertEquals('a-b-c-d-é-f', Sanitize::alphabetic('   a  b c d é     f   ', TRUE, TRUE));
        $this->assertEquals('abcéí', Sanitize::alphabetic('a<%><¡/\|*:.;,!-+>[]{}bcé#í123+'));
        $this->assertEquals('abc', Sanitize::alphabetic('abcé#í123+', FALSE));
    }
    
    public function testClearSpace() {
        $this->assertEquals('a b c', Sanitize::clearSpace(' a    b   c '));
    }
    
    public function testInteger() {
        $this->assertEquals(0, Sanitize::integer(''));
        $this->assertEquals(12345, Sanitize::integer('12345'));
        $this->assertEquals(123, Sanitize::integer('a<%><¡/\|*:.;,!-+>[]{}bcé#í123+'));
        $this->assertEquals('-1', Sanitize::integer('-1', TRUE));
        $this->assertEquals('-+-123+', Sanitize::integer('a<%><¡/\|*:.;,!-+>[]{}bcé#í-123+', TRUE));
    }
    
    public function testUrl() {
        $this->assertEquals('$-_.+!*\'(),{}|\\^~[]`<>#%";/?:@&=/', Sanitize::url('$-_.+!*\'(),{}|\\^~[]`<>#%";/?:@&='));
        $this->assertEquals('http://www.softn.red/', Sanitize::url('http://www.softn.red/áé'));
    }
    
    public function testArrayList() {
        $this->assertEquals([], Sanitize::arrayList('abc'));
        $this->assertEquals([
            1,
            2,
            3,
        ], Sanitize::arrayList([
            1,
            2,
            3,
        ]));
        $this->assertEquals([
            3 => 1,
            4 => 2,
            5 => 3,
        ], Sanitize::arrayList([
            3 => 1,
            4 => 2,
            5 => 3,
        ]));
    }
    
    public function testFloat() {
        $this->assertEquals(1.55, Sanitize::float('1.55'));
        $this->assertEquals('.-+123.55+', Sanitize::float('a<%><¡/\|*:.;,!-+>[]{}bcé#í123.55+'));
    }
    
    public function testEmail() {
        $this->assertEquals('nicolas@softn.red', Sanitize::email('nicolas@softn.red'));
        $this->assertEquals('nicolas@softn.red', Sanitize::email('nicolasáé@softn.red'));
        $this->assertEquals('nicolas-cms@softn.red', Sanitize::email('nicolas-cms@softn.red'));
        $this->assertEquals('nicolas1234@softn.red', Sanitize::email('nicolas1234@softn.red'));
    }
    
}
