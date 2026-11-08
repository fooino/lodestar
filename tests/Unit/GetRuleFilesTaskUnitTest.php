<?php

namespace Fooino\Lodestar\Tests\Unit;

use Fooino\Lodestar\Tasks\GetRuleFilesTask;

describe('GetRuleFilesTask', function () {

    it('returns the rule files in filename order', function () {

        $rules = (new GetRuleFilesTask())->run();
        $sorted = $rules;

        sort(array: $sorted);

        expect($rules)->not->toBeEmpty();
        expect($rules)->toBe($sorted);
        expect(basename(path: $rules[0]))->toBe('00-principles.md');
    });
});
