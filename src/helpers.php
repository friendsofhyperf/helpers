<?php

declare(strict_types=1);
/**
 * This file is part of hyperf/helpers.
 *
 * @link     https://github.com/friendsofhyperf/helpers
 * @document https://github.com/friendsofhyperf/helpers/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */
use Hyperf\HttpMessage\Cookie\Cookie;

/*
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

if (! function_exists('cookie')) {
    /**
     * Create a new cookie instance.
     *
     * @param null|string $name
     * @param null|string $value
     * @param int $minutes
     * @param null|string $path
     * @param null|string $domain
     * @param null|bool $secure
     * @param bool $httpOnly
     * @param bool $raw
     * @param null|string $sameSite
     * @return \Hyperf\HttpMessage\Cookie\Cookie|\Hyperf\HttpMessage\Cookie\CookieJarInterface
     */
    function cookie($name = null, $value = null, $minutes = 0, $path = null, $domain = null, $secure = null, $httpOnly = true, $raw = false, $sameSite = null)
    {
        $cookie = app(\Hyperf\HttpMessage\Cookie\CookieJarInterface::class);

        if (is_null($name)) {
            return $cookie;
        }

        $time = ($minutes == 0) ? 0 : $minutes * 60;

        return new \Hyperf\HttpMessage\Cookie\Cookie($name, $value, $time, $path, $domain, $secure, $httpOnly, $raw, $sameSite);
    }
}

if (! function_exists('session')) {
    /**
     * Get / set the specified session value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param null|array|string $key
     * @param mixed $default
     * @return \Hyperf\Contract\SessionInterface|mixed
     */
    function session($key = null, $default = null)
    {
        $session = app(\Hyperf\Contract\SessionInterface::class);

        if (is_null($key)) {
            return $session;
        }

        if (is_array($key)) {
            return $session->put($key);
        }

        return $session->get($key, $default);
    }
}

if (! function_exists('today')) {
    /**
     * Create a new Carbon instance for the current date.
     *
     * @param null|\DateTimeZone|string $tz
     * @return \Carbon\Carbon
     */
    function today($tz = null)
    {
        return \Carbon\Carbon::today($tz);
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
