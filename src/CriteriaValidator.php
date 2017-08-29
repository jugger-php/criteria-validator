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
        $column = $crit->getColumn();
        $min = (float) $crit->getMin();
        $max = (float) $crit->getMax();
        $value = (float) $row[$column] ?? null;
        if ($value) {
            return $min < $value && $value < $max;
        }
        return false;
    }

    public static function validateCompareCriteria(CompareCriteria $crit, $row): bool
    {
        $column = $crit->getColumn();
        $value = $row[$column] ?? null;
        if (!$value) {
            return false;
        }

        $compareValue = $crit->getValue();
        switch ($crit->getOperator()) {
            case '>':
                return $value > $compareValue;
            case '>=':
                return $value >= $compareValue;
            case '<':
                return $value < $compareValue;
            case '<=':
                return $value <= $compareValue;
            case '!=':
            case '<>':
                return $value != $compareValue;
        }
    }

    public static function validateEqualCriteria(EqualCriteria $crit, $row): bool
    {
        $column = $crit->getColumn();
        $value = $row[$column] ?? null;
        if (!$value) {
            return false;
        }

        $compareValue = $crit->getValue();
        return $compareValue == $value;
    }

    public static function validateInCriteria(InCriteria $crit, $row): bool
    {
        $column = $crit->getColumn();
        $value = $row[$column] ?? null;
        if (!$value) {
            return false;
        }

        $compareValue = $crit->getValue();
        return in_array($value, $compareValue);
    }

    public static function validateLikeCriteria(LikeCriteria $crit, $row): bool
    {
        $column = $crit->getColumn();
        $value = $row[$column] ?? null;
        if (!$value) {
            return false;
        }

        $compareValue = $crit->getValue();
        return strcasecmp($value, $compareValue);
    }

    public static function validateLogicCriteria(LogicCriteria $crit, $row): bool
    {
        $operator = strtolower($crit->getOperator());
        $criterias = $crit->getValue();
        foreach ($criterias as $crit) {
            $validationResult = (new self($crit))->validate($row);
            // ИЛИ - TRUE если хотя бы один истина
            if ($operator == 'or') {
                if ($validationResult) {
                    return true;
                }
            }
            // И - FALSE если хотя бы один ложь
            elseif (!$validationResult) {
                break;
            }
        }
        return false;
    }

    public static function validateRegexpCriteria(RegexpCriteria $crit, $row): bool
    {
        $column = $crit->getColumn();
        $value = $row[$column] ?? null;
        if (!$value) {
            return false;
        }

        $regexp = $crit->getValue();
        if ($regexp{0} != '/') {
            $regexp = "/{$regexp}/";
        }
        return preg_match($regexp, $value);
    }
}
