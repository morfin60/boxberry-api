<?php

namespace Morfin60\BoxberryApi\Validation;

use Morfin60\BoxberryApi\Exception\ValidationException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Класс, используемый API для проверки входных данных
 * @author Alexander N <morfin60@gmail.com>
 * @package boxberry-api
 */
class Validator
{

    /**
     * @var Symfony\Component\Validator\Validator\RecursiveValidator $validator
     */
    private $validator;

    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    /**
     * Функция, проверяющая ImId на корректность
     * @param string $im_id
     */
    public function validateImId($im_id)
    {
        $values = [
            'ImId' => $im_id
        ];

        $constraint = new Assert\Collection([
            'ImId' => new Assert\Required([
                new Assert\NotNull(['message' => 'ImId should not be null']),
                new Assert\Type(['type' => 'string', 'message' => 'ImId should be {{ type }}'])
            ])
        ]);

        $this->validateValues($values, $constraint);
    }

    /**
     * Функция, проверяющая Zip на корректность
     * @param string|int $zip
     * @throws Morfin60\BoxberryApi\Exception\ValidationException
     */
    public function validateZip($zip)
    {
        $values = [
            'zip' => $zip
        ];

        $constraint = new Assert\Collection([
            'zip' => new Assert\Required([
                new Assert\NotNull(['message' => 'zip should not be null']),
                new Assert\Type(['type' => 'numeric', 'message' => 'zip should be {{ type }}']),
                new Assert\GreaterThan(['value' => 0, 'message' => 'zip should be greater than {{ compared_value }}'])
            ])
        ]);
        $this->validateValues($values, $constraint);
    }

    /**
     * Проверить период дат $from - $to. Даты должны быть в формате YYYYMMDD
     * @param string $from дата в формате YYYYMMDD
     * @param string $to дата в форматеYYYYMMDD
     * @throws Morfin60\BoxberryApi\Exception\ValidationException
     */
    public function validatePeriod($from, $to)
    {
        $values = [
            'from' => $from,
            'to' => $to
        ];

        $constraint = new Assert\Collection([
            'from' => new Assert\Required([
                new Assert\Type(['type' => 'string', 'message' => 'From date should be an {{ type }}']),
                new Assert\Regex(['pattern' => '~^([1-9][0-9]{3})(0[1-9]|1[0-2])([0-2][0-9]|3[01])$~', 'message' => 'From date should have format YYYYMMDD'])
            ]),
            'to' => new Assert\Required([
                new Assert\Type(['type' => 'string', 'message' => 'To date  should be an {{ type }}']),
                new Assert\Regex(['pattern' => '~^([1-9][0-9]{3})(0[1-9]|1[0-2])([0-2][0-9]|3[01])$~', 'message' => 'To date should have format YYYYMMDD'])
            ]),
        ]);

        $this->validateValues($values, $constraint);
    }

    /**
     * Проверить массив с информацией о доставке
     * @param array $delivery_info ассоциативный массив, содержащий информацию о доставке
     * @throws Morfin60\BoxberryApi\Exception\ValidationException
     */
    public function validateDeliveryInfo($delivery_info)
    {
        $values = [
            'delivery_info' => $delivery_info
        ];

        $constraint = new Assert\Collection([
            'delivery_info' => new Assert\Required([
                new Assert\Type(['type' => 'array', 'message' => 'parameters should be {{ type }}']),
                new Assert\Collection([
                    'fields' => [
                        'weight' => new Assert\Required([
                            new Assert\NotNull(['message' => 'weight should not be null']),
                            new Assert\Type(['type' => 'numeric', 'message' => 'weight should be {{ type }}'])
                        ]),
                        'target' => new Assert\Required([
                            new Assert\NotNull(['message' => 'target should not be null']),
                            new Assert\Type(['type' => 'numeric', 'message' => 'target should be {{ type }}'])
                        ])
                    ],
                    'allowExtraFields' => true,
                    'missingFieldsMessage' => 'The field {{ field }} is missing.'
                ]),
                new Assert\Collection([
                    'fields' => [
                        'targetstart' => new Assert\Required([
                            new Assert\NotNull(['message' => 'targetstart should not be null']),
                            new Assert\Type(['type' => 'numeric', 'message' => 'targetstart should be {{ type }}'])
                        ]),
                        'order_sum' => new Assert\Required([
                            new Assert\NotNull(['message' => 'order_sum should not be null']),
                            new Assert\Type(['type' => 'numeric', 'message' => 'order_sum should be {{ type }}'])
                        ]),
                        'delivery_sum' => new Assert\Required([
                            new Assert\NotNull(['message' => 'delivery_sum should not be null']),
                            new Assert\Type(['type' => 'numeric', 'message' => 'delivery_sum should be {{ type }}'])
                        ]),
                        'pay_sum' => new Assert\Required([
                            new Assert\NotNull(['message' => 'pay_sum should not be null']),
                            new Assert\Type(['type' => 'numeric', 'message' => 'pay_sum should be {{ type }}'])
                        ]),
                        'width' => new Assert\Required([
                            new Assert\NotNull(['message' => 'width should not be null']),
                            new Assert\Type(['type' => 'numeric', 'message' => 'width should be {{ type }}'])
                        ]),
                        'height' => new Assert\Required([
                            new Assert\NotNull(['message' => 'height should not be null']),
                            new Assert\Type(['type' => 'numeric', 'message' => 'height should be {{ type }}'])
                        ]),
                        'depth' => new Assert\Required([
                            new Assert\NotNull(['message' => 'depth should not be null']),
                            new Assert\Type(['type' => 'numeric', 'message' => 'depth should be {{ type }}'])
                        ]),
                        'zip' => new Assert\Required([
                            new Assert\NotNull(['message' => 'zip should not be null']),
                            new Assert\Type(['type' => 'numeric', 'message' => 'zip should be {{ type }}'])
                        ]),
                    ],
                    'allowExtraFields' => true,
                    'allowMissingFields' => true,
                    'missingFieldsMessage' => 'The field {{ field }} is missing.'
                ])
            ])
        ]);

        $this->validateValues($values, $constraint);
    }

    /**
     * Функция, проверяющая входные значения используя набор правил $constraint
     * @param array $fields ассоциативный массив полей
     * @param Symfony\Component\Validator\Constraints\Collection $constraint набор правил, по которым будет производиться проверка
     * @throws Morfin60\BoxberryApi\Exception\ValidationException
     */
    public function validateValues($values, $constraint)
    {

        $violations = $this->validator->validateValue($values, $constraint);
        $violations_list = [];
        //If failed to validate arguments then we should throw ValidationException and pass violation list to $data
        if ( 0 !== $violations->count()) {
            throw new ValidationException('Failed to validate values', 1, null, $violations);
        }
    }
}