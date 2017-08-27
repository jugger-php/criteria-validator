<?php

namespace jugger\compareCriteria;

use jugger\criteria\Criteria;
use jugger\criteria\BetweenCriteria;
use jugger\criteria\CompareCriteria;
use jugger\criteria\EqualCriteria;
use jugger\criteria\InCriteria;
use jugger\criteria\LikeCriteria;
use jugger\criteria\LogicCriteria;
use jugger\criteria\RegexpCriteria;
use jugger\validator\BaseValidator;

class CriteriaValidator extends BaseValidator
{
    protected $criteria;

    public function __construct(Criteria $criteria)
    {
        $this->criteria = $criteria;
    }

    public function validate($value): bool
    {
        if ($this->criteria instanceof BetweenCriteria) {
            return self::validateBetweenCriteria($this->criteria, $value);
        }
        else if ($this->criteria instanceof CompareCriteria) {
            return self::validateCompareCriteria($this->criteria, $value);
        }
        else if ($this->criteria instanceof EqualCriteria) {
            return self::validateEqualCriteria($this->criteria, $value);
        }
        else if ($this->criteria instanceof InCriteria) {
            return self::validateInCriteria($this->criteria, $value);
        }
        else if ($this->criteria instanceof LikeCriteria) {
            return self::validateLikeCriteria($this->criteria, $value);
        }
        else if ($this->criteria instanceof LogicCriteria) {
            return self::validateLogicCriteria($this->criteria, $value);
        }
        else if ($this->criteria instanceof RegexpCriteria) {
            return self::validateRegexpCriteria($this->criteria, $value);
        }
        else {
            throw new \Exception("Not found realisation ");
        }
    }

    public static function validateBetweenCriteria(BetweenCriteria $crit, $row): bool
    {

    }

    public static function validateCompareCriteria(CompareCriteria $crit, $row): bool
    {

    }

    public static function validateEqualCriteria(EqualCriteria $crit, $row): bool
    {

    }

    public static function validateInCriteria(InCriteria $crit, $row): bool
    {

    }

    public static function validateLikeCriteria(LikeCriteria $crit, $row): bool
    {

    }

    public static function validateLogicCriteria(LogicCriteria $crit, $row): bool
    {

    }

    public static function validateRegexpCriteria(RegexpCriteria $crit, $row): bool
    {

    }
}
