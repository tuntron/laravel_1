<?php
require __DIR__.'/bootstrap/autoload.php';

class HttpServer
{
  public static $instance;
  private $application;
  
  public function __construct() {
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    $http = new swoole_http_server("127.0.0.1", 9501); // 只侦听 localhost
    $http->on('request', function ($request, $response) use($kernel) {
      Illuminate\Http\Request::enableHttpMethodParameterOverride();
      
      $get = isset($request->get)?$request->get : [];
      $post = isset($request->post)? $request->post : [];
      $cookie = isset($request->cookie)?$request->cookie:[];
      // 文件上传暂时无法实现,swoole的file处理方式跟默认php有区别
      $file = isset($request->files)? $request->files:[];
      $server = isset($request->server) ? $request->server : [];
      $header = isset($request->header) ? $request->header : [];

      foreach ($server as $key => $value) {
        $server[strtoupper($key)] = $value;
        unset($server[$key]);
      }
      // 把头信息也加入server否者laravel取不到header信息
      foreach ($header as $key => $value) {
        $server['HTTP_'.strtoupper($key)] = $value;
      }
      
      // 代码借鉴自 Illuminate\Http\Request::capture
      $l_request= new Symfony\Component\HttpFoundation\Request($get, $post, [], $cookie, $file, $server);

      if (0 === strpos($l_request->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')
        && in_array(strtoupper($l_request->server->get('REQUEST_METHOD', 'GET')), array('PUT', 'DELETE', 'PATCH'))
        ) {
        parse_str($l_request->getContent(), $data);
      $l_request->request = new  Symfony\Component\HttpFoundation\ParameterBag($data);
    }

    $l_request=Illuminate\Http\Request::createFromBase( $l_request);

    $l_response = $kernel->handle( $l_request );

    //兼容Swoole的响应对象
    // headers
    foreach ($l_response->headers->allPreserveCase() as $name => $values) {
        foreach ($values as $value) {
            $response->header($name, $value);
        }
    }
    // cookies
    foreach ($l_response->headers->getCookies() as $cookie) {
        $response->cookie($cookie->getName(), $cookie->getValue(), $cookie->getExpiresTime(), $cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
    }

    ob_start();

    $l_response->send();
    $kernel->terminate($l_request, $l_response);

    $result = ob_get_clean();
    $response->end($result);
  });
  $http->start();
}

public static function getInstance() {
  if (!self::$instance) {
    self::$instance = new HttpServer;
  }
  return self::$instance;
}

}
HttpServer::getInstance();
