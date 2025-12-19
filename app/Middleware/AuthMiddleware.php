<?php
/**
 * Authentication Middleware
 * Checks if user is authenticated
 */

class AuthMiddleware
{
    public static function check($requiredRole = null)
    {
        if (!Session::isLoggedIn()) {
            Session::flash('error', 'Please login to continue');
            header('Location: /');
            exit;
        }

        if ($requiredRole && Session::getUserRole() !== $requiredRole) {
            Session::flash('error', 'Unauthorized access');
            header('Location: /');
            exit;
        }

        return true;
    }

    public static function guest()
    {
        if (Session::isLoggedIn()) {
            $role = Session::getUserRole();
            header("Location: /{$role}/dashboard");
            exit;
        }

        return true;
    }
}
