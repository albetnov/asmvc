<?php

namespace Albet\Asmvc\Core;

use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

class CsrfGenerator
{
    private CsrfTokenManager $csrf;

    public function __construct()
    {
        $this->csrf = new CsrfTokenManager();
    }

    /**
     * Validate the csrf
     * @return bool
     */
    public function validateCsrf(): bool
    {
        $token = new CsrfToken(session('token'), request()->input('__token__'));
        $isValid = $this->csrf->isTokenValid($token);
        $this->csrf->refreshToken(session('token'));

        return $isValid;
    }

    /**
     * Echo a csrf field html
     * @param string $uniqueId
     * @return string
     */
    public function field(?string $uniqueId = null): string
    {
        if (!$uniqueId) {
            $uniqueId = bin2hex(random_bytes(32));
        }
        $token = $this->csrf->getToken($uniqueId);
        $_SESSION['token'] = $uniqueId;

        return '<input name="__token__" type="hidden" value="' . $token . '" />';
    }
}
