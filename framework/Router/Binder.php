<?php

namespace Framework\Router;

use Framework\Injectables\Injector;
use Exception;

class Binder
{
    private static $namespace;

    private static $params;

    public static function bind(array $params, array $models)
    {
        $config = Injector::resolve("Config");
        self::$namespace = $config->getConfig("application")["model_namespace"];

        $modelsParams = self::combineParamsWithModels($params, $models);

        $result = array();
        foreach($modelsParams as $model)
        {
            if(isset($model["model"]))
            {
                array_push($result, self::resolveBind($model));
            } else {
                array_push($result, $model["param"]);
            }
        }

        return $result;
    }

    private static function combineParamsWithModels(array $params, array $models)
    {
        $result = array();
        foreach($params as $index => $param)
        {
            if(array_key_exists($param["id"], $models))
            {
                $param["model"] = $models[$param["id"]];
                array_push($result, $param);
            } else {
                array_push($result, $param);
            }
        }
        return $result;
    }

    private static function resolveBind(array $model)
    {
        $class = self::$namespace . $model["model"];
        $class = new $class();

        if(isset($class->tableKey))
        {
            $key = $class->tableKey;
        } else {
            $key = "id";
        }

        $found = $class->where($key, "=", $model["param"])->selectOne();
        if($found !== false)
        {
            return $found;
        } else {
            throw new Exception("Model not found in database", 1);
        }
    }
}
