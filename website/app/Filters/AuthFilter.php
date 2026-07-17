<?php

namespace App\Filters;

use App\Models\UniversityCollegeTutorModel;
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

        if (session()->get('role') === 'trainer') {
            $path = trim((string) $request->getUri()->getPath(), '/');

            if (str_starts_with($path, 'index.php/')) {
                $path = substr($path, 10);
            }

            $portalType = $this->resolvePortalType();

            if ($portalType === 'university' && ($path === 'trainer' || str_starts_with($path, 'trainer/'))) {
                return redirect()->to('/university-portal/dashboard');
            }

            if ($portalType !== 'university' && ($path === 'university-portal' || str_starts_with($path, 'university-portal/'))) {
                return redirect()->to('/trainer/dashboard');
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

    private function resolvePortalType(): string
    {
        if (session()->get('role') !== 'trainer') {
            return (string) (session()->get('portal_type') ?? 'trainer');
        }

        if (session()->get('portal_type') === 'university') {
            return 'university';
        }

        $universityTutorModel = new UniversityCollegeTutorModel();
        $profile = $universityTutorModel->findLinkedProfile(
            (int) session()->get('user_id'),
            (string) session()->get('email'),
            (string) session()->get('username')
        );

        if ($profile) {
            session()->set([
                'portal_type' => 'university',
                'university_tutor_id' => $profile['id'] ?? null,
                'university_reference_code' => $profile['reference_code'] ?? null,
            ]);

            return 'university';
        }

        session()->set([
            'portal_type' => 'trainer',
            'university_tutor_id' => null,
            'university_reference_code' => null,
        ]);

        return 'trainer';
    }
}
