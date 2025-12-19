<?php
/**
 * Home Controller
 * Handles the landing page
 */

class HomeController extends Controller
{
    public function index()
    {
        // Check if user is already logged in
        if (Session::isLoggedIn()) {
            $role = Session::getUserRole();
            
            switch ($role) {
                case 'admin':
                    $this->redirect('admin/dashboard');
                    break;
                case 'agent':
                    $this->redirect('agent/dashboard');
                    break;
                case 'bank':
                    $this->redirect('bank/dashboard');
                    break;
            }
        }

        // Show landing page
        echo $this->view('home');
    }
}
