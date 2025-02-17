<?php

namespace Seed\Ast;

use Seed\Core\Json\JsonSerializableType;
use Exception;
use Seed\Core\Json\JsonDecoder;

class ContainerValue extends JsonSerializableType
{
    /**
     * @var string $type
     */
    public readonly string $type;

    /**
     * @var (
     *    array<mixed>
     *   |mixed
     * ) $value
     */
    public readonly mixed $value;

    /**
     * @param array{
     *   type: string,
     *   value: (
     *    array<mixed>
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
     * @param array<mixed> $list
     * @return ContainerValue
     */
    public static function list(array $list): ContainerValue
    {
        return new ContainerValue([
            'type' => 'list',
            'value' => $list,
        ]);
    }

    /**
     * @param mixed $optional
     * @return ContainerValue
     */
    public static function optional(mixed $optional = null): ContainerValue
    {
        return new ContainerValue([
            'type' => 'optional',
            'value' => $optional,
        ]);
    }

    /**
     * @param mixed $_unknown
     * @return ContainerValue
     */
    public static function _unknown(mixed $_unknown): ContainerValue
    {
        return new ContainerValue([
            'type' => '_unknown',
            'value' => $_unknown,
        ]);
    }

    /**
     * @return bool
     */
    public function isList_(): bool
    {
        return is_array($this->value) && $this->type === 'list';
    }

    /**
     * @return array<mixed>
     */
    public function asList_(): array
    {
        if (!(is_array($this->value) && $this->type === 'list')) {
            throw new Exception(
                "Expected list; got " . $this->type . "with value of type " . get_debug_type($this->value),
            );
        }

        return $this->value;
    }

    /**
     * @return bool
     */
    public function isOptional(): bool
    {
        return is_null($this->value) && $this->type === 'optional';
    }

    /**
     * @return mixed
     */
    public function asOptional(): mixed
    {
        if (!(is_null($this->value) && $this->type === 'optional')) {
            throw new Exception(
                "Expected optional; got " . $this->type . "with value of type " . get_debug_type($this->value),
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
            case 'list':
                $value = $this->value;
                $result['list'] = $value;
                break;
            case 'optional':
                $value = $this->asOptional();
                $result['optional'] = $value;
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
            case 'list':
                $args['type'] = 'list';
                if (!array_key_exists('list', $data)) {
                    throw new Exception(
                        "JSON data is missing property 'list'",
                    );
                }

                $args['list'] = $data['list'];
                break;
            case 'optional':
                $args['type'] = 'optional';
                if (!array_key_exists('optional', $data)) {
                    throw new Exception(
                        "JSON data is missing property 'optional'",
                    );
                }

                $args['optional'] = $data['optional'];
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
