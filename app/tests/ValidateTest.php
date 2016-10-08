<?php
/**
 * ValidateTest.php
 */

namespace SoftnCMS\tests;

use SoftnCMS\controllers\Validate;

class ValidateTest extends \PHPUnit_Framework_TestCase {
    
    public function testInteger() {
        $this->assertTrue(Validate::integer(1234, 5));
        $this->assertTrue(Validate::integer('1234', 5));
        $this->assertTrue(Validate::integer('12345', 5, TRUE));
        $this->assertFalse(Validate::integer(1234.5, 5));
        $this->assertFalse(Validate::integer('a', 5));
        $this->assertFalse(Validate::integer('a', 5, TRUE));
    }
    
    public function testLength(){
        $this->assertTrue(Validate::length('abcd', FALSE));
        $this->assertTrue(Validate::length(12345, FALSE, TRUE));
    }
    
    public function testLengthStrict() {
        $this->assertTrue(Validate::length('abcda', 5, TRUE));
        $this->assertTrue(Validate::length(12345, 5, TRUE));
        $this->assertFalse(Validate::length('áaá', 5, TRUE));
    }
    
    public function testLengthNoStrict() {
        $this->assertTrue(Validate::length('abcd', 5));
        $this->assertTrue(Validate::length(1234, 5));
        $this->assertFalse(Validate::length('áaáaaaaaa', 5));
    }
    
    public function testAlphanumeric() {
        $this->assertTrue(Validate::alphanumeric('ab12á', 5));
        $this->assertTrue(Validate::alphanumeric('ab12á', 5, TRUE, TRUE));
        $this->assertTrue(Validate::alphanumeric('ab12a', 5, FALSE, TRUE));
        $this->assertFalse(Validate::alphanumeric(123, 5));
        $this->assertFalse(Validate::alphanumeric('á', 5, FALSE));
        $this->assertFalse(Validate::alphanumeric('á13', 5, TRUE, TRUE));
    }
    
    public function testAlphabetic() {
        $this->assertTrue(Validate::alphabetic('abc', 5));
        $this->assertTrue(Validate::alphabetic('abc', 5, FALSE));
        $this->assertTrue(Validate::alphabetic('abcde', 5, TRUE, TRUE));
        $this->assertFalse(Validate::alphabetic('abc', 5, TRUE, TRUE));
        $this->assertFalse(Validate::alphabetic('áéó', 5, FALSE));
        $this->assertFalse(Validate::alphabetic(1234, 5));
    }
    
    public function testEmail() {
        $this->assertTrue(Validate::email('nicolas@softn.red'));
        $this->assertTrue(Validate::email('nicolas@softn.red.co'));
        $this->assertTrue(Validate::email('nicolas-cms@softn.red'));
        $this->assertTrue(Validate::email('nicolas1234@softn.red'));
        $this->assertFalse(Validate::email('nicolasáé@softn.red'));
        $this->assertFalse(Validate::email('nicolas@softn'));
        $this->assertFalse(Validate::email('sadas'));
    }
    
    public function testUrl() {
        $this->assertTrue(Validate::url('http://softn.red/'));
        $this->assertTrue(Validate::url('http://softn-cms.red/'));
        $this->assertTrue(Validate::url('http://softn123.red/'));
        $this->assertTrue(Validate::url('http://softn.red.co/'));
        $this->assertTrue(Validate::url('http://softn.red/test/'));
        $this->assertTrue(Validate::url('http://softn.red/test/?p=1'));
        $this->assertFalse(Validate::url('http://softn.red'));
        $this->assertFalse(Validate::url('http://sóftn.red/'));
        $this->assertFalse(Validate::url('softn.red'));
        $this->assertFalse(Validate::url('softn.red/test'));
    }
    
    public function testBoolean(){
        $this->assertTrue(Validate::boolean('on'));
        $this->assertTrue(Validate::boolean('yes'));
        $this->assertTrue(Validate::boolean(TRUE));
        $this->assertTrue(Validate::boolean(1));
        $this->assertTrue(Validate::boolean('1'));
        $this->assertFalse(Validate::boolean('abc123*/-'));
        $this->assertFalse(Validate::boolean(''));
        $this->assertFalse(Validate::boolean('no'));
        $this->assertFalse(Validate::boolean('off'));
        $this->assertFalse(Validate::boolean(NULL));
        $this->assertFalse(Validate::boolean(0));
        $this->assertFalse(Validate::boolean(FALSE));
        $this->assertFalse(Validate::boolean('0'));
    }
}
