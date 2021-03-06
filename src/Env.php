<?php

namespace kwm\system;


class Env
{

    const EVN_SYSTEM_ENVIRON = 'KWM_SYSTEM_ENVIRON';

    const EVN_SYSTEM_NAME = 'KWM_SYSTEM_NAME';

    const EVN_SYSTEM_CONFIG = 'KWM_SYSTEM_CONFIG_PATH';

    const EVN_SYSTEM_RUNTIME = 'KWM_SYSTEM_RUNTIME_PATH';

    /** @var string 环境配置文件目录 */
    protected static $configPath;

    /** @var string  runtime 目录 */
    protected static $runtimePath;

    /**
     * 获取当前环境名称
     *
     * 优先顺序：环境变量 > php.ini 配置
     * @return string
     */
    public static function environ()
    {
        return getenv(self::EVN_SYSTEM_ENVIRON) ?: get_cfg_var(self::EVN_SYSTEM_ENVIRON);
    }

    /**
     * 获取当前系统应用名称（仅从环境变量获取）
     *
     * @return string
     */
    public static function name()
    {
        return getenv(self::EVN_SYSTEM_NAME);
    }

    /**
     * 获取环境配置文件目录
     *
     * 需要配置系统应用名称后，才会使用独立配置文件
     * > 第一次获取时，需要传入默认配置文件目录
     * @param string $path 默认配置文件目录
     * @return string
     */
    public static function configPath($path = '')
    {
        if (!is_null(self::$configPath)) {
            return self::$configPath;
        }

        if ($name = self::name()) {
            $path = get_cfg_var(self::EVN_SYSTEM_CONFIG) . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR;
        }

        return self::$configPath = $path . self::environ() . DIRECTORY_SEPARATOR;
    }

    /**
     * 获取 runtime 目录路径
     * > 第一次获取时，需要传入默认配置文件目录
     * @param string $default 默认目录路径
     * @return string
     */
    public static function runtimePath($default = '')
    {
        if (!is_null(self::$runtimePath)) {
            return self::$runtimePath;
        }

        return self::$runtimePath = ($name = self::name()) && ($systemRuntime = get_cfg_var(self::EVN_SYSTEM_RUNTIME))
            ? $systemRuntime . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . self::environ() . DIRECTORY_SEPARATOR
            : $default;
    }
}