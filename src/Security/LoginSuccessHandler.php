<?php 

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Routing\RouterInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $roles = $token->getRoleNames();
        
        // Rediriger en fonction du rôle
        if (in_array('ROLE_ADMIN', $roles)) {
            return new RedirectResponse($this->router->generate('app_accueil_admin'));
        } elseif (in_array('ROLE_RESPONSABLE', $roles)) {
            return new RedirectResponse($this->router->generate('app_accueil_uti'));
        }

        // Par défaut, rediriger vers la page d'accueil (ou une autre page)
        return new RedirectResponse($this->router->generate('home'));
    }
}
