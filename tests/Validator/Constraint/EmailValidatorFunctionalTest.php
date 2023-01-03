<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Validator\Constraint;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Model\User;
use Webmunkeez\SecurityBundle\Validator\Constraint\Email;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class EmailValidatorFunctionalTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = static::getContainer()->get('test_validator');
    }

    public function testValidateAttributeShouldSucceed(): void
    {
        $user = new User(Uuid::v4(), 'role', 'hello@yannissgarra.com', '@Password2!');

        $violations = $this->validator->validate($user);

        $this->assertCount(0, $violations);
    }

    public function testValidateAttributeWithWrongEmailFormatShouldFail(): void
    {
        $user = new User(Uuid::v4(), 'role', 'email', '@Password2!');

        $violations = $this->validator->validate($user);

        $this->assertCount(1, $violations);
        $this->assertSame((new Email())->message, $violations[0]->getMessage());
    }

    public function testValidateAttributeWithNotExistingEmailMXShouldFail(): void
    {
        $user = new User(Uuid::v4(), 'role', 'contact@example.com', '@Password2!');

        $violations = $this->validator->validate($user);

        $this->assertCount(1, $violations);
        $this->assertSame((new Email())->message, $violations[0]->getMessage());
    }
}
