<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CorsFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $origin = $request->getHeaderLine('Origin');

        if ($origin !== '') {
            $response = service('response');

            $response
                ->setHeader('Access-Control-Allow-Origin', 'http://localhost:5173')
                ->setHeader('Access-Control-Allow-Credentials', 'true')
                ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept');

            if ($request->getMethod() === 'OPTIONS') {
                return $response;
            }
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $origin = $request->getHeaderLine('Origin');

        if ($origin !== '') {
            $response
                ->setHeader('Access-Control-Allow-Origin', 'http://localhost:5173')
                ->setHeader('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }
}