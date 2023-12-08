<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use ZxcvbnPhp\Zxcvbn;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class PasswordStrengthValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PasswordStrength) {
            throw new UnexpectedTypeException($constraint, PasswordStrength::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (false === is_string($value)) {
            // throw this exception if your validator cannot handle
            // the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');
        }

        $zxcvbn = new Zxcvbn();

        $weak = $zxcvbn->passwordStrength($value);

        if ($weak['score'] < $constraint->strength) {
            $this->context->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
