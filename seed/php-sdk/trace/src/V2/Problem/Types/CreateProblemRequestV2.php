<?php

namespace Seed\V2\Problem\Types;

use Seed\Core\Json\JsonSerializableType;
use Seed\Core\Json\JsonProperty;
use Seed\Problem\Types\ProblemDescription;
use Seed\Core\Types\ArrayType;
use Seed\Commons\Types\Language;

class CreateProblemRequestV2 extends JsonSerializableType
{
    /**
     * @var string $problemName
     */
    #[JsonProperty('problemName')]
    public string $problemName;

    /**
     * @var ProblemDescription $problemDescription
     */
    #[JsonProperty('problemDescription')]
    public ProblemDescription $problemDescription;

    /**
     * @var mixed $customFiles
     */
    #[JsonProperty('customFiles')]
    public mixed $customFiles;

    /**
     * @var array<TestCaseTemplate> $customTestCaseTemplates
     */
    #[JsonProperty('customTestCaseTemplates'), ArrayType([TestCaseTemplate::class])]
    public array $customTestCaseTemplates;

    /**
     * @var array<TestCaseV2> $testcases
     */
    #[JsonProperty('testcases'), ArrayType([TestCaseV2::class])]
    public array $testcases;

    /**
     * @var array<value-of<Language>> $supportedLanguages
     */
    #[JsonProperty('supportedLanguages'), ArrayType(['string'])]
    public array $supportedLanguages;

    /**
     * @var bool $isPublic
     */
    #[JsonProperty('isPublic')]
    public bool $isPublic;

    /**
     * @param array{
     *   problemName: string,
     *   problemDescription: ProblemDescription,
     *   customFiles: mixed,
     *   customTestCaseTemplates: array<TestCaseTemplate>,
     *   testcases: array<TestCaseV2>,
     *   supportedLanguages: array<value-of<Language>>,
     *   isPublic: bool,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->problemName = $values['problemName'];
        $this->problemDescription = $values['problemDescription'];
        $this->customFiles = $values['customFiles'];
        $this->customTestCaseTemplates = $values['customTestCaseTemplates'];
        $this->testcases = $values['testcases'];
        $this->supportedLanguages = $values['supportedLanguages'];
        $this->isPublic = $values['isPublic'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
