<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Validator\Constraint;

use Egulias\EmailValidator\EmailValidator as Validator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class EmailValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Email) {
            throw new UnexpectedTypeException($constraint, Email::class);
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

        $validator = new Validator();

        $isValid = $validator->isValid($value, new MultipleValidationWithAnd([
            new RFCValidation(),
            new DNSCheckValidation(),
        ]));

        if (false === $isValid) {
            $this->context->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
