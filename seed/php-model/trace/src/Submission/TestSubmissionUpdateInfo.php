<?php

namespace Seed\Submission;

use Seed\Core\Json\JsonSerializableType;
use Exception;
use Seed\Core\Json\JsonDecoder;

class TestSubmissionUpdateInfo extends JsonSerializableType
{
    /**
     * @var string $type
     */
    public readonly string $type;

    /**
     * @var (
     *    value-of<RunningSubmissionState>
     *   |null
     *   |mixed
     *   |GradedTestCaseUpdate
     *   |RecordedTestCaseUpdate
     * ) $value
     */
    public readonly mixed $value;

    /**
     * @param array{
     *   type: string,
     *   value: (
     *    value-of<RunningSubmissionState>
     *   |null
     *   |mixed
     *   |GradedTestCaseUpdate
     *   |RecordedTestCaseUpdate
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
     * @param value-of<RunningSubmissionState> $running
     * @return TestSubmissionUpdateInfo
     */
    public static function running(string $running): TestSubmissionUpdateInfo
    {
        return new TestSubmissionUpdateInfo([
            'type' => 'running',
            'value' => $running,
        ]);
    }

    /**
     * @return TestSubmissionUpdateInfo
     */
    public static function stopped(): TestSubmissionUpdateInfo
    {
        return new TestSubmissionUpdateInfo([
            'type' => 'stopped',
            'value' => null,
        ]);
    }

    /**
     * @param mixed $errored
     * @return TestSubmissionUpdateInfo
     */
    public static function errored(mixed $errored): TestSubmissionUpdateInfo
    {
        return new TestSubmissionUpdateInfo([
            'type' => 'errored',
            'value' => $errored,
        ]);
    }

    /**
     * @param GradedTestCaseUpdate $gradedTestCase
     * @return TestSubmissionUpdateInfo
     */
    public static function gradedTestCase(GradedTestCaseUpdate $gradedTestCase): TestSubmissionUpdateInfo
    {
        return new TestSubmissionUpdateInfo([
            'type' => 'gradedTestCase',
            'value' => $gradedTestCase,
        ]);
    }

    /**
     * @param RecordedTestCaseUpdate $recordedTestCase
     * @return TestSubmissionUpdateInfo
     */
    public static function recordedTestCase(RecordedTestCaseUpdate $recordedTestCase): TestSubmissionUpdateInfo
    {
        return new TestSubmissionUpdateInfo([
            'type' => 'recordedTestCase',
            'value' => $recordedTestCase,
        ]);
    }

    /**
     * @return TestSubmissionUpdateInfo
     */
    public static function finished(): TestSubmissionUpdateInfo
    {
        return new TestSubmissionUpdateInfo([
            'type' => 'finished',
            'value' => null,
        ]);
    }

    /**
     * @param mixed $_unknown
     * @return TestSubmissionUpdateInfo
     */
    public static function _unknown(mixed $_unknown): TestSubmissionUpdateInfo
    {
        return new TestSubmissionUpdateInfo([
            'type' => '_unknown',
            'value' => $_unknown,
        ]);
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
    public function isGradedTestCase(): bool
    {
        return $this->value instanceof GradedTestCaseUpdate && $this->type === 'gradedTestCase';
    }

    /**
     * @return GradedTestCaseUpdate
     */
    public function asGradedTestCase(): GradedTestCaseUpdate
    {
        if (!($this->value instanceof GradedTestCaseUpdate && $this->type === 'gradedTestCase')) {
            throw new Exception(
                "Expected gradedTestCase; got " . $this->type . "with value of type " . get_debug_type($this->value),
            );
        }

        return $this->value;
    }

    /**
     * @return bool
     */
    public function isRecordedTestCase(): bool
    {
        return $this->value instanceof RecordedTestCaseUpdate && $this->type === 'recordedTestCase';
    }

    /**
     * @return RecordedTestCaseUpdate
     */
    public function asRecordedTestCase(): RecordedTestCaseUpdate
    {
        if (!($this->value instanceof RecordedTestCaseUpdate && $this->type === 'recordedTestCase')) {
            throw new Exception(
                "Expected recordedTestCase; got " . $this->type . "with value of type " . get_debug_type($this->value),
            );
        }

        return $this->value;
    }

    /**
     * @return bool
     */
    public function isFinished(): bool
    {
        return is_null($this->value) && $this->type === 'finished';
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
            case 'running':
                $value = $this->value;
                $result['running'] = $value;
                break;
            case 'stopped':
                $result['stopped'] = [];
                break;
            case 'errored':
                $value = $this->value;
                $result['errored'] = $value;
                break;
            case 'gradedTestCase':
                $value = $this->asGradedTestCase()->jsonSerialize();
                $result = array_merge($value, $result);
                break;
            case 'recordedTestCase':
                $value = $this->asRecordedTestCase()->jsonSerialize();
                $result = array_merge($value, $result);
                break;
            case 'finished':
                $result['finished'] = [];
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
            case 'running':
                $args['type'] = 'running';
                if (!array_key_exists('running', $data)) {
                    throw new Exception(
                        "JSON data is missing property 'running'",
                    );
                }

                $args['running'] = $data['running'];
                break;
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
            case 'gradedTestCase':
                $args['type'] = 'gradedTestCase';
                $args['gradedTestCase'] = GradedTestCaseUpdate::jsonDeserialize($data);
                break;
            case 'recordedTestCase':
                $args['type'] = 'recordedTestCase';
                $args['recordedTestCase'] = RecordedTestCaseUpdate::jsonDeserialize($data);
                break;
            case 'finished':
                $args['type'] = 'finished';
                $args['value'] = null;
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
