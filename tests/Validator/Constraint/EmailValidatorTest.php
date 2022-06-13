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
use Webmunkeez\SecurityBundle\Validator\Constraint\Email;
use Webmunkeez\SecurityBundle\Validator\Constraint\EmailValidator;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class EmailValidatorTest extends TestCase
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

    public function testValidateShouldSucceed()
    {
        $validator = new EmailValidator();

        $this->expectNotToPerformAssertions();

        $validator->validate('hello@yannissgarra.com', new Email());
    }

    public function testValidateWithWrongEmailFormatShouldFail()
    {
        $validator = new EmailValidator();
        $constraint = new Email();

        $this->constraintViolationBuilder->expects($this->once())->method('addViolation')->willReturn(null);

        $this->executionContext->expects($this->once())->method('buildViolation')->with($constraint->message)->willReturn($this->constraintViolationBuilder);

        $validator->initialize($this->executionContext);

        $validator->validate('email', $constraint);
    }

    public function testValidateWithNotExistingEmailMXShouldFail()
    {
        $validator = new EmailValidator();
        $constraint = new Email();

        $this->constraintViolationBuilder->expects($this->once())->method('addViolation')->willReturn(null);

        $this->executionContext->expects($this->once())->method('buildViolation')->with($constraint->message)->willReturn($this->constraintViolationBuilder);

        $validator->initialize($this->executionContext);

        $validator->validate('contact@example.com', $constraint);
    }
}
