<?php

namespace Seed\Ast\Types;

use Seed\Core\Json\JsonSerializableType;
use Exception;
use Seed\Core\Json\JsonDecoder;

class FieldValue extends JsonSerializableType
{
    /**
     * @var string $type
     */
    public readonly string $type;

    /**
     * @var (
     *    value-of<PrimitiveValue>
     *   |ObjectValue
     *   |mixed
     * ) $value
     */
    public readonly mixed $value;

    /**
     * @param array{
     *   type: string,
     *   value: (
     *    value-of<PrimitiveValue>
     *   |ObjectValue
     *   |mixed
     * ),
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->type = $values['type'];
        $this->value = $values['value'];
    }

    /**
     * @param value-of<PrimitiveValue> $primitiveValue
     * @return FieldValue
     */
    public static function primitiveValue(string $primitiveValue): FieldValue
    {
        return new FieldValue([
            'type' => 'primitive_value',
            'value' => $primitiveValue,
        ]);
    }

    /**
     * @param ObjectValue $objectValue
     * @return FieldValue
     */
    public static function objectValue(ObjectValue $objectValue): FieldValue
    {
        return new FieldValue([
            'type' => 'object_value',
            'value' => $objectValue,
        ]);
    }

    /**
     * @param mixed $containerValue
     * @return FieldValue
     */
    public static function containerValue(mixed $containerValue): FieldValue
    {
        return new FieldValue([
            'type' => 'container_value',
            'value' => $containerValue,
        ]);
    }

    /**
     * @param mixed $_unknown
     * @return FieldValue
     */
    public static function _unknown(mixed $_unknown): FieldValue
    {
        return new FieldValue([
            'type' => '_unknown',
            'value' => $_unknown,
        ]);
    }

    /**
     * @return bool
     */
    public function isPrimitiveValue(): bool
    {
        return $this->value instanceof PrimitiveValue && $this->type === 'primitive_value';
    }

    /**
     * @return value-of<PrimitiveValue>
     */
    public function asPrimitiveValue(): string
    {
        if (!($this->value instanceof PrimitiveValue && $this->type === 'primitive_value')) {
            throw new Exception(
                "Expected primitive_value; got " . $this->type . "with value of type " . get_debug_type($this->value),
            );
        }

        return $this->value;
    }

    /**
     * @return bool
     */
    public function isObjectValue(): bool
    {
        return $this->value instanceof ObjectValue && $this->type === 'object_value';
    }

    /**
     * @return ObjectValue
     */
    public function asObjectValue(): ObjectValue
    {
        if (!($this->value instanceof ObjectValue && $this->type === 'object_value')) {
            throw new Exception(
                "Expected object_value; got " . $this->type . "with value of type " . get_debug_type($this->value),
            );
        }

        return $this->value;
    }

    /**
     * @return bool
     */
    public function isContainerValue(): bool
    {
        return is_null($this->value) && $this->type === 'container_value';
    }

    /**
     * @return mixed
     */
    public function asContainerValue(): mixed
    {
        if (!(is_null($this->value) && $this->type === 'container_value')) {
            throw new Exception(
                "Expected container_value; got " . $this->type . "with value of type " . get_debug_type($this->value),
            );
        }

        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array
    {
        $result = [];
        $result['type'] = $this->type;

        $base = parent::jsonSerialize();
        $result = array_merge($base, $result);

        switch ($this->type) {
            case 'primitive_value':
                $value = $this->value;
                $result['primitive_value'] = $value;
                break;
            case 'object_value':
                $value = $this->asObjectValue()->jsonSerialize();
                $result = array_merge($value, $result);
                break;
            case 'container_value':
                $value = $this->value;
                $result['container_value'] = $value;
                break;
            case '_unknown':
            default:
                if (is_null($this->value)) {
                    break;
                }
                if ($this->value instanceof JsonSerializableType) {
                    $value = $this->value->jsonSerialize();
                    $result = array_merge($value, $result);
                } elseif (is_array($this->value)) {
                    $result = array_merge($this->value, $result);
                }
        }

        return $result;
    }

    /**
     * @param string $json
     */
    public static function fromJson(string $json): static
    {
        $decodedJson = JsonDecoder::decode($json);
        if (!is_array($decodedJson)) {
            throw new Exception("Unexpected non-array decoded type: " . gettype($decodedJson));
        }
        return self::jsonDeserialize($decodedJson);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function jsonDeserialize(array $data): static
    {
        $args = [];
        if (!array_key_exists('type', $data)) {
            throw new Exception(
                "JSON data is missing property 'type'",
            );
        }
        $type = $data['type'];
        if (!(is_string($type))) {
            throw new Exception(
                "Expected property 'type' in JSON data to be string, instead received " . get_debug_type($data['type']),
            );
        }

        switch ($type) {
            case 'primitive_value':
                $args['type'] = 'primitive_value';
                if (!array_key_exists('primitive_value', $data)) {
                    throw new Exception(
                        "JSON data is missing property 'primitive_value'",
                    );
                }

                $args['primitive_value'] = $data['primitive_value'];
                break;
            case 'object_value':
                $args['type'] = 'object_value';
                $args['object_value'] = ObjectValue::jsonDeserialize($data);
                break;
            case 'container_value':
                $args['type'] = 'container_value';
                if (!array_key_exists('container_value', $data)) {
                    throw new Exception(
                        "JSON data is missing property 'container_value'",
                    );
                }

                $args['container_value'] = $data['container_value'];
                break;
            case '_unknown':
            default:
                $args['type'] = '_unknown';
                $args['value'] = $data;
        }

        // @phpstan-ignore-next-line
        return new static($args);
    }
}
