<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        // Check role if specified
        if (!empty($arguments)) {
            $requiredRole = $arguments[0];
            $userRole = session()->get('role');

            if (!$this->hasRole($userRole, $requiredRole)) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }

    private function hasRole($userRole, $requiredRole)
    {
        $roleHierarchy = [
            'customer'  => 1,
            'student'   => 2,
            'trainer'   => 3,
            'sub-admin' => 4,
            'admin'     => 5,
        ];

        $userLevel = $roleHierarchy[$userRole] ?? 0;
        $requiredLevel = $roleHierarchy[$requiredRole] ?? 0;

        return $userLevel >= $requiredLevel;
    }
}
