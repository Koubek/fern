<?php

namespace Seed\Submission;

use Seed\Core\Json\JsonSerializableType;
use Exception;
use Seed\Core\Json\JsonDecoder;

class TestSubmissionStatus extends JsonSerializableType
{
    /**
     * @var string $type
     */
    public readonly string $type;

    /**
     * @var (
     *    null
     *   |mixed
     *   |value-of<RunningSubmissionState>
     *   |array<string, mixed>
     * ) $value
     */
    public readonly mixed $value;

    /**
     * @param array{
     *   type: string,
     *   value: (
     *    null
     *   |mixed
     *   |value-of<RunningSubmissionState>
     *   |array<string, mixed>
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
     * @return TestSubmissionStatus
     */
    public static function stopped(): TestSubmissionStatus
    {
        return new TestSubmissionStatus([
            'type' => 'stopped',
            'value' => null,
        ]);
    }

    /**
     * @param mixed $errored
     * @return TestSubmissionStatus
     */
    public static function errored(mixed $errored): TestSubmissionStatus
    {
        return new TestSubmissionStatus([
            'type' => 'errored',
            'value' => $errored,
        ]);
    }

    /**
     * @param value-of<RunningSubmissionState> $running
     * @return TestSubmissionStatus
     */
    public static function running(string $running): TestSubmissionStatus
    {
        return new TestSubmissionStatus([
            'type' => 'running',
            'value' => $running,
        ]);
    }

    /**
     * @param array<string, mixed> $testCaseIdToState
     * @return TestSubmissionStatus
     */
    public static function testCaseIdToState(array $testCaseIdToState): TestSubmissionStatus
    {
        return new TestSubmissionStatus([
            'type' => 'testCaseIdToState',
            'value' => $testCaseIdToState,
        ]);
    }

    /**
     * @param mixed $_unknown
     * @return TestSubmissionStatus
     */
    public static function _unknown(mixed $_unknown): TestSubmissionStatus
    {
        return new TestSubmissionStatus([
            'type' => '_unknown',
            'value' => $_unknown,
        ]);
    }

    /**
     * @return bool
     */
    public function isStopped(): bool
    {
        return is_null($this->value) && $this->type === 'stopped';
    }

    /**
     * @return bool
     */
    public function isErrored(): bool
    {
        return is_null($this->value) && $this->type === 'errored';
    }

    /**
     * @return mixed
     */
    public function asErrored(): mixed
    {
        if (!(is_null($this->value) && $this->type === 'errored')) {
            throw new Exception(
                "Expected errored; got " . $this->type . "with value of type " . get_debug_type($this->value),
            );
        }

        return $this->value;
    }

    /**
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->value instanceof RunningSubmissionState && $this->type === 'running';
    }

    /**
     * @return value-of<RunningSubmissionState>
     */
    public function asRunning(): string
    {
        if (!($this->value instanceof RunningSubmissionState && $this->type === 'running')) {
            throw new Exception(
                "Expected running; got " . $this->type . "with value of type " . get_debug_type($this->value),
            );
        }

        return $this->value;
    }

    /**
     * @return bool
     */
    public function isTestCaseIdToState(): bool
    {
        return is_array($this->value) && $this->type === 'testCaseIdToState';
    }

    /**
     * @return array<string, mixed>
     */
    public function asTestCaseIdToState(): array
    {
        if (!(is_array($this->value) && $this->type === 'testCaseIdToState')) {
            throw new Exception(
                "Expected testCaseIdToState; got " . $this->type . "with value of type " . get_debug_type($this->value),
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
            case 'stopped':
                $result['stopped'] = [];
                break;
            case 'errored':
                $value = $this->value;
                $result['errored'] = $value;
                break;
            case 'running':
                $value = $this->value;
                $result['running'] = $value;
                break;
            case 'testCaseIdToState':
                $value = $this->value;
                $result['testCaseIdToState'] = $value;
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
            case 'stopped':
                $args['type'] = 'stopped';
                $args['value'] = null;
                break;
            case 'errored':
                $args['type'] = 'errored';
                if (!array_key_exists('errored', $data)) {
                    throw new Exception(
                        "JSON data is missing property 'errored'",
                    );
                }

                $args['errored'] = $data['errored'];
                break;
            case 'running':
                $args['type'] = 'running';
                if (!array_key_exists('running', $data)) {
                    throw new Exception(
                        "JSON data is missing property 'running'",
                    );
                }

                $args['running'] = $data['running'];
                break;
            case 'testCaseIdToState':
                $args['type'] = 'testCaseIdToState';
                if (!array_key_exists('testCaseIdToState', $data)) {
                    throw new Exception(
                        "JSON data is missing property 'testCaseIdToState'",
                    );
                }

                $args['testCaseIdToState'] = $data['testCaseIdToState'];
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
