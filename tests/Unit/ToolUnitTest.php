<?php

use Fooino\Lodestar\Enums\Tool;

describe('Tool', function () {

    it('maps each concrete tool to one task', function () {

        foreach ([Tool::Kilo, Tool::Cursor, Tool::Claude] as $tool) {

            expect($tool->tasks())->toHaveCount(1);
        }
    });

    it('expands all into every concrete tool', function () {

        expect(Tool::All->tasks())->toHaveCount(3);
        expect(Tool::All->tasks())->toHaveKeys([
            'kilo adapter',
            'cursor adapter',
            'claude adapter',
        ]);
    });

    it('resolves a tool from its value', function () {

        expect(Tool::tryFrom(value: 'kilo'))->toBe(Tool::Kilo);
        expect(Tool::tryFrom(value: 'nope'))->toBeNull();
    });
});
