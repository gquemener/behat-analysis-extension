<?php

namespace GildasQ\BehatAnalysisExtension\Compliance\Rule;

final class AnalysisResult
{
    const PASS = 0;
    const FAIL = 1;

    private $result;
    private $reasons = [];

    private function __construct(int $result, array $reasons = [])
    {
        $this->result = $result;
        $this->reasons = $reasons;
    }

    public static function pass()
    {
        return new self(self::PASS);
    }

    public static function fail(array $reasons)
    {
        return new self(self::FAIL, $reasons);
    }

    public function isPassed()
    {
        return self::PASS === $this->result;
    }

    public function reasons()
    {
        return $this->reasons;
    }
}
