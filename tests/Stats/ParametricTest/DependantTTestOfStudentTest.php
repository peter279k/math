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

use Malenki\Math\Stats\ParametricTest\DependantTTestOfStudent;
use Malenki\Math\Stats\Stats;

class DependantTTestOfStudentTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateStudentDependantsShouldSuccess()
    {
        $t = new DependantTTestOfStudent();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\DependantTTestOfStudent',
            $t
        );
    }

    public function testAddingTwoEqualSamplesShouldSuccess()
    {
        $t = new DependantTTestOfStudent();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\DependantTTestOfStudent',
            $t->add(new Stats(array(6, 3, 4)))
        );
        
        $t = new DependantTTestOfStudent();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\DependantTTestOfStudent',
            $t->add(array(6, 3, 4))->add(array(7, 5, 9))
        );
        
        
        $t = new DependantTTestOfStudent();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\DependantTTestOfStudent',
            $t->set(array(6, 3, 4), array(7, 5, 9))
        );
        
        $t = new DependantTTestOfStudent();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\DependantTTestOfStudent',
            $t->set(new Stats(array(6, 3, 4)), new Stats(array(7, 5, 9)))
        );
    }


    /**
     * @expectedException \RuntimeException
     */
    public function testAddingTwoNonEqualSamplesShouldFail()
    {
        $t = new DependantTTestOfStudent();
        $t->add(new Stats(array(1, 2, 3)));
        $t->add(new Stats(array(1, 2, 3, 4)));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAddingMoreThanTwoSamplesShouldFail()
    {
        $t = new DependantTTestOfStudent();
        $t->add(new Stats(array(1, 2, 3)));
        $t->add(new Stats(array(1, 2, 3)));
        $t->add(new Stats(array(1, 2, 3)));
    }


    public function testGettingCountShouldSuccess()
    {
        $t = new DependantTTestOfStudent();
        $t
            ->add(array(6, 3, 4, 7, 8, 9, 3, 6))
            ->add(array(6, 3, 4, 7, 8, 9, 3, 6));

        $this->assertCount(8, $t);
    }

    
    

}