<?php

declare(strict_types=1);
/**
 * This file is part of hyperf/helpers.
 *
 * @link     https://github.com/friendsofhyperf/helpers
 * @document https://github.com/friendsofhyperf/helpers/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */
if (! function_exists('app')) {
    /**
     * @throws TypeError
     * @return mixed|\Psr\Container\ContainerInterface
     */
    function app(string $abstract = null, array $parameters = [])
    {
        if (\Hyperf\Utils\ApplicationContext::hasContainer()) {
            $container = \Hyperf\Utils\ApplicationContext::getContainer();

            if (is_null($abstract)) {
                return $container;
            }

            if (count($parameters) == 0 && $container->has($abstract)) {
                return $container->get($abstract, $parameters);
            }

            if (method_exists($container, 'make')) {
                return call_user_func_array([$container, 'make'], [$abstract, array_values($parameters)]);
            }
        }

        if (is_null($abstract)) {
            throw new InvalidArgumentException('Invalid argument $abstract');
        }

        return new $abstract(...array_values($parameters));
    }
}

if (! function_exists('when')) {
    /**
     * @param mixed $expr
     * @param mixed $value
     * @param mixed $default
     * @return mixed
     */
    function when($expr, $value = null, $default = null)
    {
        $result = value($expr) ? $value : $default;

        return $result instanceof \Closure ? $result($expr) : $result;
    }
}

if (! function_exists('info')) {
    /**
     * @param string $message
     * @throws TypeError
     */
    function info($message, array $context = [])
    {
        return logs()->info($message, $context);
    }
}

if (! function_exists('logger')) {
    /**
     * @param null|string $message
     * @throws TypeError
     * @return \Psr\Log\LoggerInterface|void
     */
    function logger($message = null, array $context = [])
    {
        if (is_null($message)) {
            return logs();
        }

        return logs()->debug($message, $context);
    }
}

if (! function_exists('logs')) {
    /**
     * @param string $name
     * @param string $group
     * @throws TypeError
     * @return \Psr\Log\LoggerInterface
     */
    function logs($name = 'hyperf', $group = 'default')
    {
        return app(\Hyperf\Logger\LoggerFactory::class)->get($name, $group);
    }
}

if (! function_exists('now')) {
    /**
     * Create a new Carbon instance for the current time.
     *
     * @param null|\DateTimeZone|string $tz
     * @return \Carbon\Carbon
     */
    function now($tz = null)
    {
        return \Carbon\Carbon::now($tz);
    }
}

if (! function_exists('validator')) {
    /**
     * Create a new Validator instance.
     *
     * @return \Hyperf\Contract\ValidatorInterface|\Hyperf\Validation\ValidatorFactory
     */
    function validator(array $data = [], array $rules = [], array $messages = [], array $customAttributes = [])
    {
        $factory = app(\Hyperf\Validation\ValidatorFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($data, $rules, $messages, $customAttributes);
    }
}
