<?php

namespace {{namespace}}\Exceptions;

use Fooino\Core\Exceptions\FooinoException;

/**
 * The code range is {{range}}
 */
class {{class}}Exception extends FooinoException
{
    protected $message = 'msg.{{messageKey}}';

    protected $code = {{firstCode}};

    protected string $level = 'error';

    /**
     * Describe the condition that triggers this code
     */
    final public function _{{firstCode}}(): static
    {
        return $this
            ->setMessage('msg.{{messageKey}}')
            ->setCode({{firstCode}})
            ->error()
            ->setHttpStatusCode(500)
            ->shouldReport();
    }
}
