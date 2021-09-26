<?php
    class Router
    {
        private $routes;

        public function __construct()
        {
            $routesPath = ROOT.'/config/routes.php';
            $this->routes = include($routesPath);
        }

        /**
         * Возвращает строку запроса
         * @return string
         */
        private function getURI()
        {
            if (!empty($_SERVER['REQUEST_URI'])){
                return trim($_SERVER['REQUEST_URI'], '/');
            }
        }

        /**
         * Роутер приложения
         */
        public function run()
        {
            // Получить строку запроса
            $uri = $this->getURI();
            // Проверить наличие в routes.php
            foreach ($this->routes as $uriPattern => $path){
                if (preg_match("~$uriPattern~", $uri)){
                    // Получаем внутренний путь из внешнго согласно правилу.

                    $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                    // Получаем массив строк из url
                    $segments = explode('/', $internalRoute);

                    // Получаем первое значение массива segments и назначем controller
                    $controllerName = array_shift($segments).'Controller';
                    $controllerName = ucfirst($controllerName);

                    // Получаем первое значение массива segments и назначем action
                    $actionName = 'action'.ucfirst(array_shift($segments));
                    $parameters = $segments;

                    // Подключить файл класса-контроллера
                    $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';
                    if (file_exists($controllerFile)){
                        include_once($controllerFile);
                    }

                    // Создать объект, вызвать метод (action)
                    $controllerObject = new $controllerName;
                    $result = call_user_func_array(array($controllerObject, $actionName), $parameters);
                    if($result != null){
                        break;
                    }
                }
            }
        }
    }