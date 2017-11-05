<?php
/*
Copyright (c) 2014 Michel Petit <petit.michel@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

use Malenki\Math\Factorial;

class FactorialTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testFactorialWithBadArgumentThatRaisesException()
    {
        $f = new Factorial(-6);
    }

    public function testFactorialZeroEqualsOne()
    {
        $f = new Factorial(0);
        $this->assertEquals(1, $f->result);
    }

    public function testFactorialOneEqualsOne()
    {
        $f = new Factorial(1);
        $this->assertEquals(1, $f->result);
    }

    public function testFactorialVariousValidFactorials()
    {
        $f = new Factorial(2);
        $this->assertEquals(2, $f->result);
        $f = new Factorial(3);
        $this->assertEquals(6, $f->result);
        $f = new Factorial(5);
        $this->assertEquals(120, $f->result);
    }

    public function testGetShouldBeNull()
    {
        $f = new Factorial(2);
        $this->assertNull($f->__get(null));
    }

    public function testToString()
    {
        $f = new Factorial(2);
        $this->assertInternalType('string', $f->__toString());
        $this->assertSame('2', $f->__toString());
    }
}
