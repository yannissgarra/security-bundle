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
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Entity\User;
use Webmunkeez\SecurityBundle\Validator\Constraint\PasswordStrenght;
use Webmunkeez\SecurityBundle\Validator\Constraint\PasswordStrenghtValidator;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class PasswordStrenghtValidatorTest extends TestCase
{
    public function testValidate()
    {
        $validator = new PasswordStrenghtValidator();

        $this->expectNotToPerformAssertions();

        $result = $validator->validate('@Password2!', new PasswordStrenght());
    }

    public function testValidateException()
    {
        /** @var ExecutionContextInterface&MockObject $executionContext */
        $executionContext = $this->getMockBuilder(ExecutionContextInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var ConstraintViolationBuilderInterface&MockObject $constraintViolationBuilder */
        $constraintViolationBuilder = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->validator = new PasswordStrenghtValidator();
        $constraint = new PasswordStrenght();

        $constraintViolationBuilder->expects($this->exactly(4))
            ->method('addViolation')
            ->willReturn(null);

        $executionContext->expects($this->exactly(4))
            ->method('buildViolation')
            ->with($constraint->message)
            ->willReturn($constraintViolationBuilder);

        $this->validator->initialize($executionContext);
        $this->validator->validate('password', $constraint);
        $this->validator->validate('Password', $constraint);
        $this->validator->validate('Password2', $constraint);
        $this->validator->validate('Password2!', $constraint);

        $constraint = new PasswordStrenght(0);
        $this->validator->validate('password', $constraint);
    }

    public function testValidateAttribute()
    {
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();

        $user = new User('id', 'role', 'hello@yannissgarra.com', 'password');
        $violations = $validator->validate($user);
        $this->assertCount(1, $violations);
        $this->assertEquals((new PasswordStrenght())->message, $violations[0]->getMessage());

        $user = new User('id', 'role', 'hello@yannissgarra.com', 'password2');
        $violations = $validator->validate($user);
        $this->assertCount(0, $violations);
    }
}
