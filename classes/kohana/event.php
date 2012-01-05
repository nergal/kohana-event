<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Событийный класс
 *
 * @package Event
 * @author Nergal
 */
abstract class Kohana_Event
{
    /**
     * @static
     * @access protected
     * @var array
     */
    protected static $_listeners_pool = array();

    /**
     * Добавление события
     *
     * @static
     * @params string $event_name
     * @params callback $callback
     * @return void
     */
    public static function connect($event_name, $callback)
    {
        if ( ! in_array($event_name, array_keys(self::$_listeners_pool))) {
            self::$_listeners_pool[$event_name] = array();
        }

        $callbacks = & self::$_listeners_pool[$event_name];

        if ( ! in_array($callback, $callbacks)) {
            $callbacks[] = $callback;
        }
    }

    /**
     * Вызов событий с параметрами
     *
     * @static
     * @params string|array $event_names
     * @params mixed $params
     * @return void
     */
    public static function emit($event_names, $params = NULL)
    {
        $event_names = (array) $event_names;
        foreach ($event_names as $event_name) {
            // TODO: добавить уникальность точки соединения и аспекта
            if (in_array($event_name, array_keys(self::$_listeners_pool))) {
                foreach (self::$_listeners_pool[$event_name] as $event) {
                    if ($event !== NULL) {
                        if ( ! is_array($params)) {
                            $params = array($params);
                        }
                        call_user_func_array($event, $params);
                    }
                }
            }
        }
    }
}