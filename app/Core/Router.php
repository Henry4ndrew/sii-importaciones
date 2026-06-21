<?php

namespace App\Core;

class Router
{
    private $routes = [];
    private $currentRoute = null;

    /**
     * Registrar una ruta GET
     */
    public function get(string $path, array $handler): self
    {
        return $this->add('GET', $path, $handler);
    }

    /**
     * Registrar una ruta POST
     */
    public function post(string $path, array $handler): self
    {
        return $this->add('POST', $path, $handler);
    }

    /**
     * Registrar una ruta PUT
     */
    public function put(string $path, array $handler): self
    {
        return $this->add('PUT', $path, $handler);
    }

    /**
     * Registrar una ruta DELETE
     */
    public function delete(string $path, array $handler): self
    {
        return $this->add('DELETE', $path, $handler);
    }

    /**
     * Registrar una ruta con método específico
     */
    public function add(string $method, string $path, array $handler): self
    {
        // Limpiar la ruta (eliminar slash al final si no es la raíz)
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }
        
        // Asegurar que la ruta comience con /
        if ($path !== '/' && $path[0] !== '/') {
            $path = '/' . $path;
        }
        
        $this->routes[$method . ' ' . $path] = [
            'method' => $method,
            'path' => $path,
            'controller' => $handler[0],
            'action' => $handler[1],
            'params' => []
        ];
        return $this;
    }

    /**
 * Despachar la ruta solicitada
 */
public function dispatch(string $uri, string $method): void
{
    // Limpiar la URI
    $uri = parse_url($uri, PHP_URL_PATH);
    
    // Remover la base URL
    if (defined('BASE_URL') && BASE_URL !== '') {
        $uri = str_replace(BASE_URL, '', $uri);
    }
    
    // Eliminar slash al final (excepto si es solo '/')
    if ($uri !== '/' && substr($uri, -1) === '/') {
        $uri = rtrim($uri, '/');
    }
    
    $uri = trim($uri, '/');
    $uri = $uri === '' ? '/' : '/' . $uri;

    // Buscar la ruta exacta primero
    $routeKey = $method . ' ' . $uri;
    
    if (isset($this->routes[$routeKey])) {
        $this->executeRoute($this->routes[$routeKey]);
        return;
    }

    // Si no se encuentra, intentar con la ruta sin el slash final
    // (por si la ruta registrada tiene slash y la URI no)
    if (substr($uri, -1) === '/') {
        $uriWithoutSlash = rtrim($uri, '/');
        $routeKeyAlt = $method . ' ' . $uriWithoutSlash;
        if (isset($this->routes[$routeKeyAlt])) {
            $this->executeRoute($this->routes[$routeKeyAlt]);
            return;
        }
    } else {
        // Si la URI no tiene slash, intentar con slash al final
        $uriWithSlash = $uri . '/';
        $routeKeyAlt = $method . ' ' . $uriWithSlash;
        if (isset($this->routes[$routeKeyAlt])) {
            $this->executeRoute($this->routes[$routeKeyAlt]);
            return;
        }
    }

    // Buscar rutas con parámetros (ej: /productos/{id})
    foreach ($this->routes as $key => $route) {
        $pattern = $this->convertToRegex($route['path']);
        if (preg_match($pattern, $uri, $matches)) {
            $params = [];
            foreach ($matches as $key => $value) {
                if (!is_int($key)) {
                    $params[$key] = $value;
                }
            }
            $route['params'] = $params;
            $this->executeRoute($route);
            return;
        }
    }

    // Si no se encuentra, mostrar 404
    http_response_code(404);
    view('error404', ['titulo' => 'Página no encontrada']);
}

    /**
     * Convertir ruta con parámetros a regex
     */
    private function convertToRegex(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    /**
     * Ejecutar el controlador y método de la ruta
     */
    private function executeRoute(array $route): void
    {
        $controllerClass = $route['controller'];
        $action = $route['action'];
        $params = $route['params'] ?? [];

        if (!class_exists($controllerClass)) {
            throw new \Exception("Controlador '$controllerClass' no encontrado");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $action)) {
            throw new \Exception("Método '$action' no encontrado en '$controllerClass'");
        }

        $controller->$action(...array_values($params));
    }

    /**
     * Obtener todas las rutas registradas
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}