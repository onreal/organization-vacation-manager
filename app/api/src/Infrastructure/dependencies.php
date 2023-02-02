<?php

use Up\Application\Application\ApplicationService;
use Up\Application\Application\ApplicationValidator;
use Up\Application\User\UserService;
use Up\Application\User\UserValidator;
use Up\Core\Domain\Application\IApplicationRepository;
use Up\Core\Domain\Application\IApplicationService;
use Up\Core\Domain\Application\IApplicationValidator;
use Up\Core\Domain\LogAction\ILogActionRepository;
use Up\Core\Domain\User\IUserRepository;
use Up\Core\Domain\User\IUserService;
use Up\Core\Domain\User\IUserValidator;
use Up\Core\Email\IMailService;
use Up\Persistence\Command\ApplicationCommand;
use Up\Persistence\Command\LogActionCommand;
use Up\Persistence\Command\UserCommand;
use Up\Persistence\DatabaseFactory;
use Up\Persistence\IDatabase;
use Up\Service\Mail\EmailService;
use Up\Service\Mail\IMail;
use Up\Service\Mail\MailPHPMailer;

return [
    IDatabase::class => DI\create(DatabaseFactory::class),
    IUserRepository::class => DI\create(UserCommand::class)
        ->constructor(DI\get(IDatabase::class)),
    IApplicationRepository::class => DI\create(ApplicationCommand::class)
    ->constructor(DI\get(IDatabase::class)),
    ILogActionRepository::class => DI\create(LogActionCommand::class)
        ->constructor(DI\get(IDatabase::class)),
    IMail::class => DI\create(MailPHPMailer::class),
    IMailService::class => DI\create(EmailService::class)
        ->constructor(DI\get(IMail::class)),
    IUserService::class => DI\autowire(UserService::class),
    IApplicationService::class => DI\autowire(ApplicationService::class),
    IUserValidator::class => DI\autowire(UserValidator::class),
    IApplicationValidator::class => DI\autowire(ApplicationValidator::class),
];
