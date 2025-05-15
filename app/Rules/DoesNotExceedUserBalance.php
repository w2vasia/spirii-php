<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\User;

class DoesNotExceedUserBalance implements ValidationRule
{
    protected User $user;
    protected string $message;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->message = "The transaction would exceed the current balance for the User.";
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $currentBalance = $this->user->getTransactionsBalance();
        $newBalance = $currentBalance - $value;

        if ($newBalance < 0) {
            $fail($this->message);
        }
    }
}
