<?php

use Up\Application\Domain\Application\ApplicationService;
use Up\Application\Domain\Application\ApplicationValidator;
use Up\Application\Domain\User\UserService;
use Up\Application\Domain\User\UserValidator;
use Up\Core\Domain\Application\IApplicationRepository;
use Up\Core\Domain\Application\IApplicationService;
use Up\Core\Domain\Application\IApplicationValidator;
use Up\Core\Domain\LogAction\ILogActionRepository;
use Up\Core\Domain\User\IUserRepository;
use Up\Core\Domain\User\IUserService;
use Up\Core\Domain\User\IUserValidator;
use Up\Core\External\IMailService;
use Up\Gateway\Email\EmailService;
use Up\Gateway\Email\IMailer;
use Up\Gateway\Email\Mailer;
use Up\Gateway\Persistence\Command\ApplicationCommand;
use Up\Gateway\Persistence\Command\LogActionCommand;
use Up\Gateway\Persistence\Command\UserCommand;
use Up\Gateway\Persistence\DatabaseFactory;
use Up\Gateway\Persistence\IDatabase;

return [
    IDatabase::class              => DI\create(DatabaseFactory::class),
    IUserRepository::class        => DI\create(UserCommand::class)->constructor(DI\get(IDatabase::class)),
    IApplicationRepository::class => DI\create(ApplicationCommand::class)->constructor(DI\get(IDatabase::class)),
    ILogActionRepository::class   => DI\create(LogActionCommand::class)->constructor(DI\get(IDatabase::class)),
    IMailer::class                  => DI\create(Mailer::class),
    IMailService::class           => DI\create(EmailService::class)->constructor(DI\get(IMailer::class)),
    IUserService::class           => DI\autowire(UserService::class),
    IApplicationService::class    => DI\autowire(ApplicationService::class),
    IUserValidator::class         => DI\autowire(UserValidator::class),
    IApplicationValidator::class  => DI\autowire(ApplicationValidator::class),
];
