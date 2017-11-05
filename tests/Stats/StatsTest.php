<?php
/*
 * Copyright (c) 2014 Michel Petit <petit.michel@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

use Malenki\Math\Stats\Stats;

class StatsTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateWithoutArgShouldSuccess()
    {
        $s = new Stats();
        $this->assertInstanceOf('Malenki\Math\Stats\Stats', $s);
    }

    public function testInstanciateWithArgShouldSuccess()
    {
        $s = new Stats(array(1,4,7,5,8));
        $this->assertInstanceOf('Malenki\Math\Stats\Stats', $s);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInstanciateWithArrayHavingBadValueTypeMustFail()
    {
        $s = new Stats(array(1,4,7,5,'height'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInstanciateWithNoArrayShouldFail()
    {
        $s = new Stats(1, 2, 3);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testMegingWithArrayHavingNonNumericValuesShouldFail()
    {
        $s = new Stats();
        $s->merge(array(1,4,7,5,'height'));
    }

    public function testCountingValuesInsideCollectionShouldSuccess()
    {
        $s = new Stats();
        $this->assertCount(0, $s);
        $s->merge(array(3,8,4,2,6,3,8,5,4,3,3,7,8,4,8,1));
        $this->assertCount(16, $s);
        $s = new Stats(array(1,4,7,5,8));
        $this->assertCount(5, $s);
    }

    public function testIfAllValuesArePositiveOrNotShouldSuccess()
    {
        $s = new Stats(array(1,3,5,3.0,5,6,3.2));
        $this->assertTrue($s->allPositive());
        $s = new Stats(array(1,3,5,-2,3,-6));
        $this->assertFalse($s->allPositive());
    }


    public function testIfAllValuesAreIntegerOrNotShouldSuccess()
    {
        $s = new Stats(array(1,3,5,3.0,6));
        $this->assertTrue($s->allInteger());
        $s = new Stats(array(1,3,5.2,3,6));
        $this->assertFalse($s->allInteger());
    }

    public function testGettingSumOfSampleShouldSuccess()
    {
        $s = new Stats(array(1,1,3,2,3));
        $this->assertEquals(10, $s->sum());
        $this->assertEquals(10, $s->sum);
    }

    public function testGettingSquareSumOfSampleShouldSuccess()
    {
        $s = new Stats(array(1,1,3,2,3));
        $this->assertEquals(24, $s->squareSum());
        $this->assertEquals(24, $s->square_sum);
        $this->assertEquals(24, $s->sum2);
        
        $s = new Stats(array(1,1,-3,-2,3));
        $this->assertEquals(24, $s->squareSum());
        $this->assertEquals(24, $s->square_sum);
        $this->assertEquals(24, $s->sum2);
    }

    public function testGettingPowerSumOfSampleShouldSuccess()
    {
        $s = new Stats(array(1,1,-3,-2,3));
        $this->assertEquals(-6, $s->powerSum(3));
    }

    public function testComputingArithmeticMeanShouldSuccess()
    {
        $s = new Stats(array(1, 2, 3, 4));
        $this->assertEquals(2.5, $s->arithmeticMean());
        $this->assertEquals(2.5, $s->arithmetic_mean);
        $this->assertEquals(2.5, $s->mean());
        $this->assertEquals(2.5, $s->mean);

        $s = new Stats(array(1, 2, 4, 8, 16));
        $this->assertEquals(6.2, $s->arithmeticMean());
        $this->assertEquals(6.2, $s->arithmetic_mean);
        $this->assertEquals(6.2, $s->mean());
        $this->assertEquals(6.2, $s->mean);
    }

    public function testCenteringValuesShouldSuccess()
    {
        $s = new Stats(array(6,8,4,5,3,4));
        $this->assertEquals(array((float)1,(float)3,(float)-1,(float)0,(float)-2,(float)-1), $s->center());
        $s = new Stats(array(8,12,9,11,6,8));
        $this->assertEquals(array((float)-1,(float)3,(float)0,(float)2,(float)-3,(float)-1), $s->center);
        $s = new Stats(array(13,9,11,8,7,12));
        $this->assertEquals(array((float)3,(float)-1,(float)1,(float)-2,(float)-3,(float)2), $s->center);
    }

    public function testComputeHarmonicMeanShouldSuccess()
    {
        $s = new Stats(array(1, 2, 4));
        $this->assertEquals(12/7, $s->harmonicMean());
        $this->assertEquals(12/7, $s->harmonic_mean);
        $this->assertEquals(12/7, $s->subcontrary_mean);
        $this->assertEquals(12/7, $s->H);
    }

    public function testComputeGeometricMeanShouldSuccess()
    {
        $s = new Stats(array(4, 1, 1/32));
        $this->assertEquals(1/2, $s->geometricMean());
        $this->assertEquals(1/2, $s->geometric_mean);
        $this->assertEquals(1/2, $s->G);

        $s = new Stats(array(2, 8));
        $this->assertEquals(4, $s->geometricMean());
        $this->assertEquals(4, $s->geometric_mean);
        $this->assertEquals(4, $s->G);
    }

    public function testComputingQuadraticMeanShouldSuccess()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals((float) 4.472136, round($s->rootMeanSquare(), 6));
        $this->assertEquals((float) 4.472136, round($s->rms(), 6));
        $this->assertEquals((float) 4.472136, round($s->quadraticMean(), 6));
        $this->assertEquals((float) 4.472136, round($s->root_mean_square, 6));
        $this->assertEquals((float) 4.472136, round($s->rms, 6));
        $this->assertEquals((float) 4.472136, round($s->quadratic_mean, 6));
    }

    public function testComputingGeneralizedMeanShouldSuccess()
    {
        $this->markTestIncomplete();
    }

    public function testGettingHeronianMeanShouldSuccess()
    {
        $s = new Stats(array(2, 7));
        $this->assertEquals((float) 4.247, round($s->heronianMean(), 3));
        $this->assertEquals((float) 4.247, round($s->heronian(), 3));
        $this->assertEquals((float) 4.247, round($s->heronian_mean, 3));
        $this->assertEquals((float) 4.247, round($s->heronian, 3));
        $s = new Stats(array(5, 10));
        $this->assertEquals((float) 7.357, round($s->heronianMean(), 3));
        $this->assertEquals((float) 7.357, round($s->heronian(), 3));
        $this->assertEquals((float) 7.357, round($s->heronian_mean, 3));
        $this->assertEquals((float) 7.357, round($s->heronian, 3));
    }

    public function testGettingLehmerMeanShouldSucess()
    {
        $this->markTestIncomplete();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGettingLehmerMeanWithNegativeNumbersShouldFail()
    {
        $s = new Stats(array(2,4,-3,5));
        $s->lehmerMean(3);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGettingLehmerMeanWithBadParameterShouldFail()
    {
        $s = new Stats(array(2,4,3,5));
        $s->lehmerMean('foo');
    }
    
    public function testGettingContraharmonicMeanShouldSucess()
    {
        $this->markTestIncomplete();
    }

    public function testEqualityOfLehmerMeanWithOtherMeans()
    {
        $s = new Stats(array(2,5));
        $this->assertEquals($s->harmonic_mean, $s->lehmerMean(0));
        $this->assertEquals($s->geometric_mean, $s->lehmerMean(1/2));
        $this->assertEquals($s->mean, $s->lehmerMean(1));
        $this->assertEquals($s->contraharmonic_mean, $s->lehmerMean(2));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGettingHeronianMeanWithCollectionHavingNegativeNumbersShouldFail()
    {
        $s = new Stats(array(-2, 7));
        $s->heronianMean();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGettingHeronianMeanWithCollectionHavingMoreThanTwoElementsShouldFail()
    {
        $s = new Stats(array(2, 7, 3));
        $s->heronianMean();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGettingHeronianMeanWithCollectionHavingLessThanTwoElementsShouldFail()
    {
        $s = new Stats(array(2));
        $s->heronianMean();
    }

    /**
     *@expectedException \InvalidArgumentException
     */
    public function testComputingGeneralizedMeanWithPEqualZeroShouldFail()
    {
        $s = new Stats(array(1,2,3,4,3,7,2));
        $s->generalizedMean(0);
    }

    /**
     *@expectedException \InvalidArgumentException
     */
    public function testComputingGeneralizedMeanWithPNegativeShouldFail()
    {
        $s = new Stats(array(1,2,3,4,3,7,2));
        $s->generalizedMean(-3);
    }

    /**
     *@expectedException \RuntimeException
     */
    public function testComputingGeneralizedMeanWithCollectionHavingNegativeNumbersShouldFail()
    {
        $s = new Stats(array(1,2,-3,4,3,7,2));
        $s->generalizedMean(3);
    }

    public function testGettingModeFromIntegersSetShouldSuccess()
    {
        $s = new Stats(array(1,2,3,4,3,7,2));
        $this->assertContains(2, $s->mode());
        $this->assertContains(3, $s->mode());
        $this->assertNotContains(7, $s->mode());
        $this->assertContains(2, $s->mode);
        $this->assertContains(3, $s->mode);
        $this->assertNotContains(7, $s->mode);
        
        $s = new Stats(array(1,2,3,4,5,7,2));
        $this->assertContains(2, $s->mode());
        $this->assertNotContains(7, $s->mode());
        $this->assertContains(2, $s->mode);
        $this->assertNotContains(7, $s->mode);
    }
    
    public function testGettingModeFromFloatsSetShouldSuccess()
    {
        $this->markTestIncomplete();
    }
    public function testIfModeIsUnimodalShouldSuccess()
    {
        $s = new Stats(array(1,2,3,4,3,7,2));
        $this->assertFalse($s->isUnimodal());
        $this->assertFalse($s->is_unimodal);
        $this->assertFalse($s->unimodal);
        
        $s = new Stats(array(1,2,3,4,3,7,2,4));
        $this->assertFalse($s->isUnimodal());
        $this->assertFalse($s->is_unimodal);
        $this->assertFalse($s->unimodal);
        
        $s = new Stats(array(1,2,3,4,5,7,2));
        $this->assertTrue($s->isUnimodal());
        $this->assertTrue($s->is_unimodal);
        $this->assertTrue($s->unimodal);
    }

    public function testIfModeIsBimodalShouldSuccess()
    {
        $s = new Stats(array(1,2,3,4,3,7,2));
        $this->assertTrue($s->isBimodal());
        $this->assertTrue($s->is_bimodal);
        $this->assertTrue($s->bimodal);
        
        $s = new Stats(array(1,2,3,4,3,7,2,4));
        $this->assertFalse($s->isBimodal());
        $this->assertFalse($s->is_bimodal);
        $this->assertFalse($s->bimodal);
        
        $s = new Stats(array(1,2,3,4,5,7,2));
        $this->assertFalse($s->isBimodal());
        $this->assertFalse($s->is_bimodal);
        $this->assertFalse($s->bimodal);
    }


    public function testIfModeIsMultimodalShouldSuccess()
    {
        $s = new Stats(array(1,2,3,4,3,7,2,4));
        $this->assertTrue($s->isMultimodal());
        $this->assertTrue($s->is_multimodal);
        $this->assertTrue($s->multimodal);
        
        $s = new Stats(array(1,2,3,4,3,7,2));
        $this->assertFalse($s->isMultimodal());
        $this->assertFalse($s->is_multimodal);
        $this->assertFalse($s->multimodal);
        
        $s = new Stats(array(1,2,3,4,5,7,2));
        $this->assertFalse($s->isMultimodal());
        $this->assertFalse($s->is_multimodal);
        $this->assertFalse($s->multimodal);
    }

    public function testGettingRangeShouldSuccess()
    {
        $s = new Stats(array(1,2,3,4,3,7,2));
        $this->assertEquals(6, $s->range());
        $this->assertEquals(6, $s->range);
    }

    public function testGettingVarianceShouldSuccess()
    {
        $s = new Stats(array(1,2,3));
        $this->assertEquals((float) 0.667, round($s->variance(), 3));
        $this->assertEquals((float) 0.667, round($s->variance, 3));
        $this->assertEquals((float) 0.667, round($s->var, 3));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGettingVarianceFromVoidCollectionShouldFail()
    {
        $s = new Stats();
        $s->variance;
    }

    public function testGettingStandardDeviationShouldSuccess()
    {
        $s = new Stats(array(1,2,3));
        $this->assertEquals((float) 0.816, round($s->standardDeviation(), 3));
        $this->assertEquals((float) 0.816, round($s->standard_deviation, 3));
        $this->assertEquals((float) 0.816, round($s->stddev, 3));
        $this->assertEquals((float) 0.816, round($s->stdev, 3));
    }

    public function testGettingSampleVarianceShouldSuccess()
    {
        $s = new Stats(array(1,2,3));
        $this->assertEquals((float) 1, $s->sampleVariance());
        $this->assertEquals((float) 1, $s->sample_variance);
        $this->assertEquals((float) 1, $s->s2);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGettingVarianceFromCollectionHavingLessThanTwoElementsShouldFail()
    {
        $s = new Stats(array(5));
        $s->sample_variance;
    }

    public function testComputingCovarianceShouldSuccess()
    {
        $s1 = new Stats(array(1,3,2,6,4));
        $arr = array(6,4,7,1,4);
        $this->assertEquals((float) -4.1, $s1->sampleCovariance($arr));
        $s2 = new Stats($arr);
        $this->assertEquals((float) -4.1, $s1->sampleCovariance($s2));

        $s1 = new Stats(array(3,4,5,7));
        $arr = array(10,11,13,14);
        $this->assertEquals(3, $s1->sampleCovariance($arr));
        $s2 = new Stats($arr);
        $this->assertEquals(3, $s1->sampleCovariance($s2));
    }

    public function testGettingKurtosisShouldSuccess()
    {
        $s = new Stats(array(1,2,3,4,5));
        $this->assertEquals((float) -1.3, $s->kurtosis());
        $this->assertEquals((float) -1.3, $s->kurtosis);

        $s = new Stats(array(1,2,3,4,500, 6));
        $this->assertEquals((float) 1.199, round($s->kurtosis(), 3));
        $this->assertEquals((float) 1.199, round($s->kurtosis, 3));
    }

    public function testGettingKurtosisTypeShouldSuccess()
    {
        $s = new Stats(array(1,2,3,4,5));
        $this->assertTrue($s->isPlatykurtic());
        $this->assertFalse($s->isLeptokurtic());
        $this->assertFalse($s->isMesokurtic());

        $s = new Stats(array(1,2,3,4,500, 6));
        $this->assertFalse($s->isPlatykurtic());
        $this->assertTrue($s->isLeptokurtic());
        $this->assertFalse($s->isMesokurtic());
    }

    public function testGettingQuartileShouldSuccess()
    {
        $s = new Stats(array(1, 11, 15, 19, 20, 24, 28, 34, 37, 47, 50, 57));
        $this->assertEquals(15, $s->quartile(1));
        $this->assertEquals(15, $s->first_quartile);
        $this->assertEquals(26, $s->quartile(2));
        $this->assertEquals(26, $s->second_quartile);
        $this->assertEquals(26, $s->median);
        $this->assertEquals(37, $s->quartile(3));
        $this->assertEquals(37, $s->third_quartile);
        $this->assertEquals(37, $s->last_quartile);

        $s = new Stats(array(1, 15, 11, 19, 20, 24, 28, 34, 37, 47, 50, 57));
        $this->assertEquals(15, $s->quartile(1));
        $this->assertEquals(15, $s->first_quartile);
        $this->assertEquals(26, $s->quartile(2));
        $this->assertEquals(26, $s->second_quartile);
        $this->assertEquals(26, $s->median);
        $this->assertEquals(37, $s->quartile(3));
        $this->assertEquals(37, $s->third_quartile);
        $this->assertEquals(37, $s->last_quartile);
    }

    public function testGettingInterquartileRangeShouldSuccess()
    {
        $s = new Stats(array(1, 11, 15, 19, 20, 24, 28, 34, 37, 47, 50, 57));
        $this->assertEquals(22, $s->interquartileRange());
        $this->assertEquals(22, $s->iqr());
        $this->assertEquals(22, $s->iqr);
        $this->assertEquals(22, $s->IQR);
        $this->assertEquals(22, $s->interquartile_range);
    }

    public function testGettingPercentileShouldSuccess()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals(1, $s->percentile(0));
        $this->assertEquals(1, $s->percentile(1));
        $this->assertEquals(1, $s->percentile(14));
        $this->assertEquals(2, $s->percentile(15));
        $this->assertEquals(2, $s->percentile(28));
        $this->assertEquals(3, $s->percentile(29));
        $this->assertEquals(3, $s->percentile(42));
        $this->assertEquals(4, $s->percentile(43));
        $this->assertEquals(4, $s->percentile(57));
        $this->assertEquals(5, $s->percentile(58));
        $this->assertEquals(5, $s->percentile(71));
        $this->assertEquals(6, $s->percentile(72));
        $this->assertEquals(6, $s->percentile(85));
        $this->assertEquals(7, $s->percentile(86));
        $this->assertEquals(7, $s->percentile(99.999999));
        $this->assertEquals(7, $s->percentile(100));
        $this->assertEquals($s->median, $s->percentile(57));
    }

    /**
     * @expectedException \OutOfRangeException
     */
    public function testGettingPercentileLessThanZeroShouldFail()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $s->percentile(-6);
    }

    /**
     * @expectedException \OutOfRangeException
     */
    public function testGettingPercentileGreaterThanHundredShouldFail()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $s->percentile(101);
    }


    public function testGettingSkewnessShouldSuccess()
    {
        $s = new Stats(array(1, 11, 15, 19, 20, 24, 28, 34, 37, 47, 50, 57));
        $this->assertEquals((float) 0.181, round($s->skewness(), 3));
        $this->assertEquals((float) 0.181, round($s->skew(), 3));
        $this->assertEquals((float) 0.181, round($s->skew, 3));
        $this->assertEquals((float) 0.181, round($s->skewness, 3));
    }

    public function testIfSkewIsNegativeShouldSuccess()
    {
        $s = new Stats(array(1, 11, 15, 19, 20, 24, 28, 34, 37, 47, 50, 57));
        $this->assertFalse($s->isLeftSkewed());
        $this->assertFalse($s->is_left_skewed);
        $this->assertFalse($s->left_skewed);
        $this->assertFalse($s->is_negative_skew);
        $this->assertFalse($s->negative_skew);
        $this->assertFalse($s->is_left_tailed);
        $this->assertFalse($s->left_tailed);
        $this->assertFalse($s->skewed_to_the_left);

        $s = new Stats(array(1,1001,1002,1003));
        $this->assertTrue($s->isLeftSkewed());
        $this->assertTrue($s->is_left_skewed);
        $this->assertTrue($s->left_skewed);
        $this->assertTrue($s->is_negative_skew);
        $this->assertTrue($s->negative_skew);
        $this->assertTrue($s->is_left_tailed);
        $this->assertTrue($s->left_tailed);
        $this->assertTrue($s->skewed_to_the_left);
    }

    public function testIfSkewIsPositiveShouldSuccess()
    {
        $s = new Stats(array(1, 11, 15, 19, 20, 24, 28, 34, 37, 47, 50, 57));
        $this->assertTrue($s->isRightSkewed());
        $this->assertTrue($s->is_right_skewed);
        $this->assertTrue($s->right_skewed);
        $this->assertTrue($s->is_positive_skew);
        $this->assertTrue($s->positive_skew);
        $this->assertTrue($s->is_right_tailed);
        $this->assertTrue($s->right_tailed);
        $this->assertTrue($s->skewed_to_the_right);

        $s = new Stats(array(1,2,3,1000));
        $this->assertTrue($s->isRightSkewed());
        $this->assertTrue($s->is_right_skewed);
        $this->assertTrue($s->right_skewed);
        $this->assertTrue($s->is_positive_skew);
        $this->assertTrue($s->positive_skew);
        $this->assertTrue($s->is_right_tailed);
        $this->assertTrue($s->right_tailed);
        $this->assertTrue($s->skewed_to_the_right);
    }

    public function testGettingFrequency()
    {
        $s = new Stats(array(1,3,1,5,3,3));
        $this->assertEquals(array('1' => 2, '3' => 3, '5' => 1), $s->frequency());
        $this->assertEquals(array('1' => 2, '3' => 3, '5' => 1), $s->frequency);
        $this->assertEquals(count($s), array_sum($s->frequency));
    }

    public function testGettingRelativeFrequencyShouldSuccess()
    {
        $s = new Stats(array(1,3,1,5,3,3));
        $this->assertEquals(array('1' => 1/3, '3' => 1/2, '5' => 1/6), $s->f());
        $this->assertEquals(array('1' => 1/3, '3' => 1/2, '5' => 1/6), $s->f);
        $this->assertEquals(1, array_sum($s->f));
        $this->assertEquals(array('1' => 1/3, '3' => 1/2, '5' => 1/6), $s->relativeFrequency());
        $this->assertEquals(array('1' => 1/3, '3' => 1/2, '5' => 1/6), $s->relative_frequency);
        $this->assertEquals(1, array_sum($s->relative_frequency));
    }

    public function testGettingCumulativeFrequencyShouldSuccess()
    {
        $s = new Stats(array(1,3,1,5,3,3));
        $this->assertEquals(array('1' => 2, '3' => 5, '5' => 6), $s->cumulativeFrequency());
        $this->assertEquals(array('1' => 2, '3' => 5, '5' => 6), $s->cumulative_frequency);
        $s = new Stats(array(1,2,2,3,3,3,5,5,5,5,5,6,6,6,6,6,6,9,9,9,9,9,9,9,9,9,4,4,4,4));
        $this->assertEquals(array('1' => 1, '2' => 3, '3' => 6, '5' => 11, '6' => 17, '9' => 26, '4' => 30), $s->cumulativeFrequency());
        $this->assertEquals(array('1' => 1, '2' => 3, '3' => 6, '5' => 11, '6' => 17, '9' => 26, '4' => 30), $s->cumulative_frequency);
    }

    public function testGettingCoefficientOfVariationShouldSuccess()
    {
        $s = new Stats(array(1,2,5,3,7,4,3,6));
        $this->assertEquals((float)0.490, (float) round($s->coefficientOfVariation(), 3));
        $this->assertEquals((float)0.490, (float) round($s->cv(), 3));
        $this->assertEquals((float)0.490, (float) round($s->coefficient_of_variation, 3));
        $this->assertEquals((float)0.490, (float) round($s->cv, 3));
        $s = new Stats(range(1,9));
        $this->assertEquals((float)0.516, (float) round($s->coefficientOfVariation(), 3));
        $this->assertEquals((float)0.516, (float) round($s->cv(), 3));
        $this->assertEquals((float)0.516, (float) round($s->coefficient_of_variation, 3));
        $this->assertEquals((float)0.516, (float) round($s->cv, 3));
    }

    public function testGettingCoefficientOfDispersionShouldSuccess()
    {
        $this->markTestIncomplete();
    }

    public function testGettingPPMCCShouldSuccess()
    {
        $this->markTestIncomplete();
    }

    public function testGetWithNameIsMidRange()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals(4, $s->__get('midrange'));
    }

    public function testGetWithNameIsPlatykurtic()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals(4, $s->__get('is_platykurtic'));
    }

    public function testGetWithNameIsLeptokurtic()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertFalse($s->__get('is_leptokurtic'));
    }

    public function testGetWithNameIsMesokurtic()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertFalse($s->__get('is_mesokurtic'));
    }

    public function testGetWithNameIsIndexOfDispersion()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals(1, $s->__get('coefficient_of_dispersion'));
    }

    public function testGetWithNameIsPearsonsR()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals(1, $s->__get('pearsons_rho'));
    }

    public function testGetWithNameIsNull()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertNull($s->__get('no'));
    }

    public function testMinShouldGetTheMinValue()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals(1, $s->min());
    }

    public function testMaxShouldGetTheMaxValue()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals(7, $s->max());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddShouldNotBeTheNumbericParameter()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $s->add('no');
    }

    public function testGeneralizedMeanShouldReturnPowResult()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals(4, $s->generalizedMean(1));
    }

    public function testPowerMeanShouldReturnGeneralizedMean()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals(4, $s->powerMean(1));
    }

    public function testLehmerShoudReturnLehmerMean()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals(4, $s->lehmer(1));
    }

    public function testContraharmonicShoudReturnLehmerMean()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals(5, $s->contraharmonic());
    }

    public function testMidextremeShouldReturnMidRange()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals(4, $s->midextreme()); 
    }

    public function testPopulationVarianceShouldReturnVariance()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals(4, $s->populationVariance()); 
    }

    public function testPopulationCovarianceShouldReturnCovariance()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals(4, $s->populationCovariance(array(1,2,3,4,5,6,7))); 
    }

    public function testStddevShouldReturnStandardDeviation()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals(2, $s->stddev());
    }

    public function testStdevShouldReturnStandardDeviation()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals(2, $s->stdev());
    }

    public function testSigmaShouldReturnStandardDeviation()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals(2, $s->sigma());
    }

    public function testS2ShouldReturnSampleDeviation()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertGreaterThanOrEqual(4, $s->s2());
    }

    public function testPercentileWithNIs50Percent()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertGreaterThanOrEqual(4, $s->percentile(50));

        $s = new Stats(array(1,2,3,4,5,6));
        $this->assertGreaterThanOrEqual(3, $s->percentile(50));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPearsonsR()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $s->pearsonsR('no');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCovarianceDataWithNoArray()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $s->covariance('no'); 
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testModeShouldBeNull()
    {
        $s = new Stats(array(1,1.1,1.2));
        $s->mode();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetWithIndexMustBeInteger()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $s->get('no');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetWithIndexMustBeNullOrPositiveInteger()
    {
        $s = new Stats(array(1,2,3,4,5,6,7));
        $s->get(-1);
    }
}
