<?php

namespace App\Asmvc\Core;

use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

class CsrfGenerator
{
    private CsrfTokenManager $csrf;
    private const tokenModifier = "__token__";

    public function __construct()
    {
        $this->csrf = new CsrfTokenManager();
    }

    /**
     * Validate the csrf
     */
    public function validateCsrf(): bool
    {
        $token = new CsrfToken(session('token'), request()->getInput(self::tokenModifier));
        $isValid = $this->csrf->isTokenValid($token);
        $this->csrf->refreshToken(session('token'));

        return $isValid;
    }

    /**
     * Echo a csrf field html
     */
    public function field(?string $uniqueId = null): string
    {
        if (!$uniqueId) {
            $uniqueId = bin2hex(random_bytes(32));
        }
        $token = $this->csrf->getToken($uniqueId);
        $_SESSION['token'] = $uniqueId;

        return '<input name="' . self::tokenModifier . '" type="hidden" value="' . $token . '" />';
    }
}
