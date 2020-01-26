<?php

namespace Processor;

use Context\ApplicationContext;
use Entity\User;
use Helper\TextHelper;

class UserProcessor implements ProcessorInterface
{
    private ApplicationContext $applicationContext;

    const USER_FIRSTNAME = '[user:first_name]';
    const USER_LASTNAME = '[user:last_name]';

    public function __construct()
    {
        $this->applicationContext = ApplicationContext::getInstance();
    }

    public function replacePlaceholders(string $text, array $data)
    {
        if(isset($data['user']) && $data['user'] instanceof User) {
            return $this->getTextWithValues($text, $data['user']);
        } else {
            return $this->getTextWithValues($text, $this->applicationContext->getCurrentUser());
        }
    }

    protected function getTextWithValues(string $text, User $user) {
        if(TextHelper::doesContain(self::USER_FIRSTNAME, $text)) {
            $text = TextHelper::searchAndReplace(self::USER_FIRSTNAME, $user->getFirstname(), $text);
        }
        if(TextHelper::doesContain(self::USER_LASTNAME, $text)) {
            $text = TextHelper::searchAndReplace(self::USER_LASTNAME, $user->getLastname(), $text);
        }

        return $text;
    }
}