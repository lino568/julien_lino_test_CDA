<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Dtos\UtilisateurDto;
use App\Models\Impl\UtilisateurDAOImpl;
use App\Models\Interfaces\UtilisateurDAO;

class LoginController extends Controller
{
    private UtilisateurDAO $utilisateurDAO;

    public function __construct()
    {
        parent::__construct();
        $this->utilisateurDAO = new UtilisateurDAOImpl();
    }

    public function login()
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->response(false, 'Requête invalide');
        }

        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (empty($data['email']) || empty($data['motDePasse'])) {
            $this->response(false, 'Veuillez remplir tous les champs.');
        }

        $userConnected = $this->utilisateurDAO->findByEmail($data['email']);

        if (!$userConnected || !password_verify($data['motDePasse'], $userConnected->getPassword())) {
            $this->response(false, ' Identifiants incorrects, veuillez vérifier vos informations.');
        }

        $this->session::set('isLoggedIn', true);
        $this->session::set('user', new UtilisateurDto($userConnected));

        
        $this->response(true, null, '/dashboard');
    }

    private function response(bool $response, ?string $message = null, ?string $redirect = null)
    {
        $responsData = [
            'success' => $response
        ];

        if ($message) {
            $responsData['message'] = $message;
        }

        if ($redirect) {
            $responsData['redirect'] = $redirect;
        }

        header('Content-Type: application/json');
        echo json_encode($responsData);
        exit;
    }
}
