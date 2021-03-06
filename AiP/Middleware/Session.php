<?php

namespace AiP\Middleware;

class Session
{
    private $app;

    public function __construct($app)
    {
        if (!is_callable($app))
            throw new InvalidApplicationException('invalid app supplied');

        $this->app = $app;
    }

    public function __invoke($context)
    {
        if (isset($context['mfs.session']))
            throw new Session\LogicException('"mfs.session" key is already occupied in context');

        $ck = $context['mfs.session'] = new Session\Engine($context);

        $result = call_user_func($this->app, $context);

        // Append cookie-headers
        $result[1] = array_merge($result[1], $ck->_getHeaders());

        return $result;
    }
}
