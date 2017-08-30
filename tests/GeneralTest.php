<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use jugger\criteria\BetweenCriteria;
use jugger\criteria\CompareCriteria;
use jugger\criteria\EqualCriteria;
use jugger\criteria\InCriteria;
use jugger\criteria\LikeCriteria;
use jugger\criteria\LogicCriteria;
use jugger\criteria\RegexpCriteria;
use jugger\criteria\SimpleLogicCriteria;
use jugger\criteriaValidator\CriteriaValidator;

class GeneralTest extends TestCase
{
    public function getData()
    {
        return [
            [
                'id' => 1,
                'name' => 'test1',
                'date' => new \DateTime("2017-08-27"),
            ],
            [
                'id' => 2,
                'name' => 'test1',
                'date' => new \DateTime("2017-01-27"),
            ],
            [
                'id' => 3,
                'name' => 'test2',
                'date' => new \DateTime("2017-08-05"),
            ],
            [
                'id' => 4,
                'name' => 'test3',
                'date' => new \DateTime("2017-11-11"),
            ],
            [
                'id' => 5,
                'name' => 'another',
                'date' => new \DateTime("2011-08-27"),
            ],
        ];
    }

    public function testBetween()
    {
        $data = $this->getData();
        $validator = new CriteriaValidator(
            new BetweenCriteria("id", 2, 6)
        );

        $this->assertFalse($validator->validate($data[0]));
        $this->assertFalse($validator->validate($data[1]));
        $this->assertTrue($validator->validate($data[2]));
        $this->assertTrue($validator->validate($data[3]));
        $this->assertTrue($validator->validate($data[4]));
    }

    public function testCompare()
    {
        $data = $this->getData();
        $validator = new CriteriaValidator(
            new CompareCriteria("date", ">", new \DateTime('2017-08-01'))
        );
        $this->assertTrue($validator->validate($data[0]));
        $this->assertFalse($validator->validate($data[1]));
        $this->assertTrue($validator->validate($data[2]));
        $this->assertTrue($validator->validate($data[3]));
        $this->assertFalse($validator->validate($data[4]));
    }

    public function testEqual()
    {
        $data = $this->getData();
        $validator = new CriteriaValidator(
            new EqualCriteria("name", "test1")
        );
        $this->assertTrue($validator->validate($data[0]));
        $this->assertTrue($validator->validate($data[1]));
        $this->assertFalse($validator->validate($data[2]));
        $this->assertFalse($validator->validate($data[3]));
        $this->assertFalse($validator->validate($data[4]));
    }

    public function testIn()
    {
        $data = $this->getData();
        $validator = new CriteriaValidator(
            new InCriteria("id", [4,5])
        );
        $this->assertFalse($validator->validate($data[0]));
        $this->assertFalse($validator->validate($data[1]));
        $this->assertFalse($validator->validate($data[2]));
        $this->assertTrue($validator->validate($data[3]));
        $this->assertTrue($validator->validate($data[4]));
    }

    public function testLike()
    {
        $data = $this->getData();
        $validator = new CriteriaValidator(
            new LikeCriteria("name", "test")
        );
        $this->assertTrue($validator->validate($data[0]));
        $this->assertTrue($validator->validate($data[1]));
        $this->assertTrue($validator->validate($data[2]));
        $this->assertTrue($validator->validate($data[3]));
        $this->assertFalse($validator->validate($data[4]));

        // procent not working
        $validator = new CriteriaValidator(
            new LikeCriteria("name", "%es%")
        );
        $this->assertFalse($validator->validate($data[0]));
        $this->assertFalse($validator->validate($data[1]));
        $this->assertFalse($validator->validate($data[2]));
        $this->assertFalse($validator->validate($data[3]));
        $this->assertFalse($validator->validate($data[4]));
    }

    public function testLogic()
    {
        $data = $this->getData();
        $validator = new CriteriaValidator(
            new LogicCriteria("and", [
                new LikeCriteria("name", "test1"),
                new EqualCriteria("id", 2),
            ])
        );
        $this->assertFalse($validator->validate($data[0]));
        $this->assertTrue($validator->validate($data[1]));
        $this->assertFalse($validator->validate($data[2]));
        $this->assertFalse($validator->validate($data[3]));
        $this->assertFalse($validator->validate($data[4]));
    }

    public function testSimpleLogic()
    {
        $data = $this->getData();
        $validator = new CriteriaValidator(
            new SimpleLogicCriteria([
                'or',
                '<id' => 3,
                '<date' => new \DateTime("2017-01-01"),
            ])
        );
        $this->assertTrue($validator->validate($data[0]));
        $this->assertTrue($validator->validate($data[1]));
        $this->assertFalse($validator->validate($data[2]));
        $this->assertFalse($validator->validate($data[3]));
        $this->assertTrue($validator->validate($data[4]));
    }

    public function testRegexp()
    {
        $data = $this->getData();
        $validator = new CriteriaValidator(
            new RegexpCriteria("name", "t[^c]st\\d+")
        );
        $this->assertTrue($validator->validate($data[0]));
        $this->assertTrue($validator->validate($data[1]));
        $this->assertTrue($validator->validate($data[2]));
        $this->assertTrue($validator->validate($data[3]));
        $this->assertFalse($validator->validate($data[4]));
    }
}
