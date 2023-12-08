<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Validator\Constraint;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use Webmunkeez\SecurityBundle\Validator\Constraint\PasswordStrength;
use Webmunkeez\SecurityBundle\Validator\Constraint\PasswordStrengthValidator;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class PasswordStrengthValidatorTest extends TestCase
{
    /**
     * @var ConstraintViolationBuilderInterface&MockObject
     */
    private ConstraintViolationBuilderInterface $constraintViolationBuilder;

    /**
     * @var ExecutionContextInterface&MockObject
     */
    private ExecutionContextInterface $executionContext;

    protected function setUp(): void
    {
        /** @var ConstraintViolationBuilderInterface&MockObject $constraintViolationBuilder */
        $constraintViolationBuilder = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->disableOriginalConstructor()->getMock();
        $this->constraintViolationBuilder = $constraintViolationBuilder;

        /** @var ExecutionContextInterface&MockObject $executionContext */
        $executionContext = $this->getMockBuilder(ExecutionContextInterface::class)->disableOriginalConstructor()->getMock();
        $this->executionContext = $executionContext;
    }

    public function testValidateShouldSucceed(): void
    {
        $validator = new PasswordStrengthValidator();

        $this->expectNotToPerformAssertions();

        $validator->validate('@Password2!', new PasswordStrength());
    }

    public function testValidateWithLowPassorwStrengthRequiredShouldSucceed(): void
    {
        $validator = new PasswordStrengthValidator();

        $this->expectNotToPerformAssertions();

        $validator->validate('password', new PasswordStrength(0));
    }

    public function testValidateWithLowPasswordStrengthShouldFail(): void
    {
        $validator = new PasswordStrengthValidator();
        $constraint = new PasswordStrength();

        $this->constraintViolationBuilder->expects($this->exactly(4))->method('addViolation')->willReturn(null);

        $this->executionContext->expects($this->exactly(4))->method('buildViolation')->with($constraint->message)->willReturn($this->constraintViolationBuilder);

        $validator->initialize($this->executionContext);

        $validator->validate('password', $constraint);
        $validator->validate('Password', $constraint);
        $validator->validate('Password2', $constraint);
        $validator->validate('Password2!', $constraint);
    }
}
