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
use Webmunkeez\SecurityBundle\Validator\Constraint\Email;
use Webmunkeez\SecurityBundle\Validator\Constraint\EmailValidator;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class EmailValidatorTest extends TestCase
{
    public function testValidate()
    {
        $validator = new EmailValidator();

        $this->expectNotToPerformAssertions();

        $validator->validate('hello@yannissgarra.com', new Email());
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

        $this->validator = new EmailValidator();
        $constraint = new Email();

        $constraintViolationBuilder->expects($this->exactly(2))
            ->method('addViolation')
            ->willReturn(null);

        $executionContext->expects($this->exactly(2))
            ->method('buildViolation')
            ->with($constraint->message)
            ->willReturn($constraintViolationBuilder);

        $this->validator->initialize($executionContext);
        $this->validator->validate('email', $constraint);
        $this->validator->validate('contact@example.com', $constraint);
    }

    public function testValidateAttribute()
    {
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();

        $user = new User('id', 'role', 'contact@example.com', 'password2');
        $violations = $validator->validate($user);
        $this->assertCount(1, $violations);
        $this->assertEquals((new Email())->message, $violations[0]->getMessage());

        $user = new User('id', 'role', 'hello@yannissgarra.com', 'password2');
        $violations = $validator->validate($user);
        $this->assertCount(0, $violations);
    }
}
