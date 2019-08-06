<?php
namespace App\Validator;
use App\Exceptions\Exceptions;

abstract class AbstractValidator implements ValidatorInterface
{
    /**
     * @param string $date
     * @param string $format
     * @throws \Exception
     */
    protected function validateDate (string $date, string $format)
    {
        $formattedDate = \DateTime::createFromFormat($format, $date);
        if (!$formattedDate || $formattedDate->format($format) !== $date) {
            throw new \Exception(sprintf(Exceptions::WRONG_DATE, $format));
        }
    }

    /**
     * @param string $data
     * @param string $fieldName
     * @param array $enum
     * @throws \Exception
     */
    protected function validateEnum (string $data, string $fieldName, array $enum)
    {
        if (!in_array($data, $enum)) {
            $enumAsString = '[' . implode($enum) . ']';
            throw new \Exception(sprintf(Exceptions::NOT_IN_ENUM, $fieldName, $enumAsString));
        }
    }

    /**
     * @param $data
     * @param string $fieldName
     * @throws \Exception
     */
    protected function validateTypeInt ($data, string $fieldName)
    {
        if (intval($data) != $data) {
            throw new \Exception(sprintf(Exceptions::WRONG_TYPE, $fieldName, 'Integer'));
        }
    }

    /**
     * @param $data
     * @param string $fieldName
     * @throws \Exception
     */
    protected function validateNumeric ($data, string $fieldName)
    {
        if (!is_numeric($data)) {
            throw new \Exception(sprintf(Exceptions::WRONG_TYPE, $fieldName, 'number'));
        }
    }
}