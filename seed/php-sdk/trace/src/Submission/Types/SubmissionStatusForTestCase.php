<?php

namespace Seed\Submission\Types;

use Seed\Core\Json\JsonSerializableType;
use Exception;
use Seed\Core\Json\JsonDecoder;

class SubmissionStatusForTestCase extends JsonSerializableType
{
    /**
     * @var string $type
     */
    public readonly string $type;

    /**
     * @var (
     *    TestCaseResultWithStdout
     *   |mixed
     *   |TracedTestCase
     * ) $value
     */
    public readonly mixed $value;

    /**
     * @param array{
     *   type: string,
     *   value: (
     *    TestCaseResultWithStdout
     *   |mixed
     *   |TracedTestCase
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
     * @param TestCaseResultWithStdout $graded
     * @return SubmissionStatusForTestCase
     */
    public static function graded(TestCaseResultWithStdout $graded): SubmissionStatusForTestCase
    {
        return new SubmissionStatusForTestCase([
            'type' => 'graded',
            'value' => $graded,
        ]);
    }

    /**
     * @param mixed $gradedV2
     * @return SubmissionStatusForTestCase
     */
    public static function gradedV2(mixed $gradedV2): SubmissionStatusForTestCase
    {
        return new SubmissionStatusForTestCase([
            'type' => 'gradedV2',
            'value' => $gradedV2,
        ]);
    }

    /**
     * @param TracedTestCase $traced
     * @return SubmissionStatusForTestCase
     */
    public static function traced(TracedTestCase $traced): SubmissionStatusForTestCase
    {
        return new SubmissionStatusForTestCase([
            'type' => 'traced',
            'value' => $traced,
        ]);
    }

    /**
     * @param mixed $_unknown
     * @return SubmissionStatusForTestCase
     */
    public static function _unknown(mixed $_unknown): SubmissionStatusForTestCase
    {
        return new SubmissionStatusForTestCase([
            'type' => '_unknown',
            'value' => $_unknown,
        ]);
    }

    /**
     * @return bool
     */
    public function isGraded(): bool
    {
        return $this->value instanceof TestCaseResultWithStdout && $this->type === 'graded';
    }

    /**
     * @return TestCaseResultWithStdout
     */
    public function asGraded(): TestCaseResultWithStdout
    {
        if (!($this->value instanceof TestCaseResultWithStdout && $this->type === 'graded')) {
            throw new Exception(
                "Expected graded; got " . $this->type . "with value of type " . get_debug_type($this->value),
            );
        }

        return $this->value;
    }

    /**
     * @return bool
     */
    public function isGradedV2(): bool
    {
        return is_null($this->value) && $this->type === 'gradedV2';
    }

    /**
     * @return mixed
     */
    public function asGradedV2(): mixed
    {
        if (!(is_null($this->value) && $this->type === 'gradedV2')) {
            throw new Exception(
                "Expected gradedV2; got " . $this->type . "with value of type " . get_debug_type($this->value),
            );
        }

        return $this->value;
    }

    /**
     * @return bool
     */
    public function isTraced(): bool
    {
        return $this->value instanceof TracedTestCase && $this->type === 'traced';
    }

    /**
     * @return TracedTestCase
     */
    public function asTraced(): TracedTestCase
    {
        if (!($this->value instanceof TracedTestCase && $this->type === 'traced')) {
            throw new Exception(
                "Expected traced; got " . $this->type . "with value of type " . get_debug_type($this->value),
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
            case 'graded':
                $value = $this->asGraded()->jsonSerialize();
                $result = array_merge($value, $result);
                break;
            case 'gradedV2':
                $value = $this->value;
                $result['gradedV2'] = $value;
                break;
            case 'traced':
                $value = $this->asTraced()->jsonSerialize();
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
            case 'graded':
                $args['type'] = 'graded';
                $args['graded'] = TestCaseResultWithStdout::jsonDeserialize($data);
                break;
            case 'gradedV2':
                $args['type'] = 'gradedV2';
                if (!array_key_exists('gradedV2', $data)) {
                    throw new Exception(
                        "JSON data is missing property 'gradedV2'",
                    );
                }

                $args['gradedV2'] = $data['gradedV2'];
                break;
            case 'traced':
                $args['type'] = 'traced';
                $args['traced'] = TracedTestCase::jsonDeserialize($data);
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
