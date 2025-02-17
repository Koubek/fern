<?php

namespace Seed\Submission;

use Seed\Core\Json\JsonSerializableType;
use Exception;
use Seed\Core\Json\JsonDecoder;

class InvalidRequestCause extends JsonSerializableType
{
    /**
     * @var string $type
     */
    public readonly string $type;

    /**
     * @var (
     *    SubmissionIdNotFound
     *   |CustomTestCasesUnsupported
     *   |UnexpectedLanguageError
     *   |mixed
     * ) $value
     */
    public readonly mixed $value;

    /**
     * @param array{
     *   type: string,
     *   value: (
     *    SubmissionIdNotFound
     *   |CustomTestCasesUnsupported
     *   |UnexpectedLanguageError
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
     * @param SubmissionIdNotFound $submissionIdNotFound
     * @return InvalidRequestCause
     */
    public static function submissionIdNotFound(SubmissionIdNotFound $submissionIdNotFound): InvalidRequestCause
    {
        return new InvalidRequestCause([
            'type' => 'submissionIdNotFound',
            'value' => $submissionIdNotFound,
        ]);
    }

    /**
     * @param CustomTestCasesUnsupported $customTestCasesUnsupported
     * @return InvalidRequestCause
     */
    public static function customTestCasesUnsupported(CustomTestCasesUnsupported $customTestCasesUnsupported): InvalidRequestCause
    {
        return new InvalidRequestCause([
            'type' => 'customTestCasesUnsupported',
            'value' => $customTestCasesUnsupported,
        ]);
    }

    /**
     * @param UnexpectedLanguageError $unexpectedLanguage
     * @return InvalidRequestCause
     */
    public static function unexpectedLanguage(UnexpectedLanguageError $unexpectedLanguage): InvalidRequestCause
    {
        return new InvalidRequestCause([
            'type' => 'unexpectedLanguage',
            'value' => $unexpectedLanguage,
        ]);
    }

    /**
     * @param mixed $_unknown
     * @return InvalidRequestCause
     */
    public static function _unknown(mixed $_unknown): InvalidRequestCause
    {
        return new InvalidRequestCause([
            'type' => '_unknown',
            'value' => $_unknown,
        ]);
    }

    /**
     * @return bool
     */
    public function isSubmissionIdNotFound(): bool
    {
        return $this->value instanceof SubmissionIdNotFound && $this->type === 'submissionIdNotFound';
    }

    /**
     * @return SubmissionIdNotFound
     */
    public function asSubmissionIdNotFound(): SubmissionIdNotFound
    {
        if (!($this->value instanceof SubmissionIdNotFound && $this->type === 'submissionIdNotFound')) {
            throw new Exception(
                "Expected submissionIdNotFound; got " . $this->type . "with value of type " . get_debug_type($this->value),
            );
        }

        return $this->value;
    }

    /**
     * @return bool
     */
    public function isCustomTestCasesUnsupported(): bool
    {
        return $this->value instanceof CustomTestCasesUnsupported && $this->type === 'customTestCasesUnsupported';
    }

    /**
     * @return CustomTestCasesUnsupported
     */
    public function asCustomTestCasesUnsupported(): CustomTestCasesUnsupported
    {
        if (!($this->value instanceof CustomTestCasesUnsupported && $this->type === 'customTestCasesUnsupported')) {
            throw new Exception(
                "Expected customTestCasesUnsupported; got " . $this->type . "with value of type " . get_debug_type($this->value),
            );
        }

        return $this->value;
    }

    /**
     * @return bool
     */
    public function isUnexpectedLanguage(): bool
    {
        return $this->value instanceof UnexpectedLanguageError && $this->type === 'unexpectedLanguage';
    }

    /**
     * @return UnexpectedLanguageError
     */
    public function asUnexpectedLanguage(): UnexpectedLanguageError
    {
        if (!($this->value instanceof UnexpectedLanguageError && $this->type === 'unexpectedLanguage')) {
            throw new Exception(
                "Expected unexpectedLanguage; got " . $this->type . "with value of type " . get_debug_type($this->value),
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
            case 'submissionIdNotFound':
                $value = $this->asSubmissionIdNotFound()->jsonSerialize();
                $result = array_merge($value, $result);
                break;
            case 'customTestCasesUnsupported':
                $value = $this->asCustomTestCasesUnsupported()->jsonSerialize();
                $result = array_merge($value, $result);
                break;
            case 'unexpectedLanguage':
                $value = $this->asUnexpectedLanguage()->jsonSerialize();
                $result = array_merge($value, $result);
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
            case 'submissionIdNotFound':
                $args['type'] = 'submissionIdNotFound';
                $args['submissionIdNotFound'] = SubmissionIdNotFound::jsonDeserialize($data);
                break;
            case 'customTestCasesUnsupported':
                $args['type'] = 'customTestCasesUnsupported';
                $args['customTestCasesUnsupported'] = CustomTestCasesUnsupported::jsonDeserialize($data);
                break;
            case 'unexpectedLanguage':
                $args['type'] = 'unexpectedLanguage';
                $args['unexpectedLanguage'] = UnexpectedLanguageError::jsonDeserialize($data);
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
