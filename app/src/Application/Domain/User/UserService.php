<?php

namespace Up\Application\Domain\User;

use Throwable;
use Up\Application\Models\ResponseModel;
use Up\Core\Domain\Entities\User;
use Up\Core\Domain\User\IUserRepository;
use Up\Core\Domain\User\IUserService;
use Up\Core\Domain\User\IUserValidator;

final class UserService implements IUserService
{
    /**
     * @var IUserRepository
     */
    private IUserRepository $userRepository;

    /**
     * @var IUserValidator
     */
    private IUserValidator $userValidation;

    /**
     * @param IUserRepository $userRepository
     * @param IUserValidator $userValidation
     */
    public function __construct(
        IUserRepository $userRepository,
        IUserValidator $userValidation
    ) {
        $this->userRepository = $userRepository;
        $this->userValidation = $userValidation;
    }

    /**
     * @param string $payload
     * @return ResponseModel
     */
    public function add(string $payload): ResponseModel
    {
        try {
            $errors = [];
            $user = $this->validate($errors, $payload);
            if (!empty($errors)) {
                $code = array_keys($errors)[0];
                return new ResponseModel($code, $errors[$code]);
            }

            $isExists = $this->userRepository->findByOne('email', $user->email);
            if ($isExists) {
                return new ResponseModel(400, 'user already exists');
            }

            if (!isset($user->password)) {
                return new ResponseModel(400, 'user password not set');
            }
            if (!$this->userValidation->isPasswordRequirements($user->password)) {
                return new ResponseModel(400, 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.');
            }

            if (!isset($user->role)) {
                return new ResponseModel(400, 'user role not set');
            }
            if (!$this->userValidation->roleIsValid($user->role)) {
                return new ResponseModel(400, 'provided role is not valid');
            }

            $salt = password_hash(
                $user->password,
                PASSWORD_BCRYPT,
                array('cost' => strlen($user->password))
            );

            $newUser = new User();
            $newUser->setFirstName($user->firstName);
            $newUser->setLastName($user->lastName);
            $newUser->setSalt($salt);
            $newUser->setEmail($user->email);
            $newUser->setRole($user->role);
            $userId = $this->userRepository->add($newUser);

            return new ResponseModel(201, 'OK', $this->userRepository->findByOne('userId', $userId));
        } catch (Throwable $e) {
            return new ResponseModel(500, $e->getMessage());
        }
    }

    /**
     * @param int $userId
     * @return ResponseModel
     */
    public function find(int $userId): ResponseModel
    {
        try {
            $user = $this->userRepository->findByOne('userId', $userId);
            if (!$user) {
                return new ResponseModel(404, 'user could not found');
            }
        } catch (Throwable $e) {
            return new ResponseModel(500, $e->getMessage());
        }

        return new ResponseModel(200, 'OK', $user);
    }

    /**
     * @return ResponseModel
     */
    public function fetchAll(): ResponseModel
    {
        try {
            return new ResponseModel(200, 'OK', $this->userRepository->fetchAll());
        } catch (Throwable $e) {
            return new ResponseModel(500, $e->getMessage());
        }
    }

    /**
     * @param int $userId
     * @param string $payload
     * @return ResponseModel
     */
    public function update(int $userId, string $payload): ResponseModel
    {
        try {
            $existingUser = $this->userRepository->findByOne('userId', $userId);
            if (!$existingUser) {
                return new ResponseModel(404, 'user could not found');
            }

            $validationErrors = [];
            $theUser = $this->validate($validationErrors, $payload);
            if (!empty($validationErrors)) {
                $code = array_keys($validationErrors)[0];
                return new ResponseModel($code, $validationErrors[$code], $validationErrors[$code]);
            }

            if ($theUser->firstName == $existingUser->getFirstName() &&
                $theUser->lastName == $existingUser->getLastName() &&
                $theUser->email == $existingUser->getEmail()) {
                return new ResponseModel(202, 'no need to update', $existingUser);
            }
            $existingUser->setFirstName($theUser->firstName);
            $existingUser->setLastName($theUser->lastName);
            $existingUser->setEmail($theUser->email);

            if (!isset($theUser->password) && $theUser->password) {
                if (!$this->userValidation->verifyPassword($theUser->password, $existingUser->getSalt())) {
                    if (!$this->userValidation->isPasswordRequirements($theUser->password)) {
                        return new ResponseModel(400, 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.');
                    }

                    $salt = password_hash(
                        $theUser->password,
                        PASSWORD_BCRYPT,
                        array('cost' => strlen($theUser->password))
                    );
                    $existingUser->setSalt($salt);
                }
            }

            $this->userRepository->update($existingUser);
        } catch (Throwable $e) {
            return new ResponseModel(500, $e->getMessage());
        }

        return new ResponseModel(200, 'OK', $this->userRepository->findByOne('userId', $userId));
    }

    /**
     * @param int $userId
     * @return ResponseModel
     */
    public function delete(int $userId): ResponseModel
    {
        try {
            $existingUser = $this->userRepository->findByOne('userId', $userId);
            if (!$existingUser) {
                return new ResponseModel(404, 'user could not found');
            }

            $this->userRepository->delete($existingUser);

            return new ResponseModel(204, 'OK');
        } catch (Throwable $e) {
            return new ResponseModel(500, $e->getMessage());
        }
    }

    /**
     * @param string $credentials
     * @return ResponseModel
     */
    public function login(string $credentials): ResponseModel
    {
        try {
            $login = json_decode($credentials, false);
            if (!$login) {
                return new ResponseModel(404, 'could not validate incoming credentials object');
            }

            $user = $this->userRepository->findByOne('email', $login->email);
            if (!$user) {
                return new ResponseModel(404, 'wrong password or email, please try again');
            }

            if (!$this->userValidation->verifyPassword($login->password, $user->getSalt())) {
                return new ResponseModel(400, 'wrong password or email, please try again');
            }

            return new ResponseModel(200, 'OK', $user);
        } catch (Throwable $e) {
            return new ResponseModel(500, $e->getMessage());
        }
    }

    /**
     * @param array $errors
     * @param string $payload
     * @return object|null
     */
    private function validate(array &$errors, string $payload): ?object
    {
        $user = json_decode($payload, false);
        if (!$user) {
            $errors[400] = 'could not validate incoming user object';
            return null;
        }

        if (!isset($user->firstName)) {
            $errors[400] = 'user email is not valid';
            return null;
        }
        $user->firstName = filter_var($user->firstName, FILTER_SANITIZE_STRING);
        if (!$this->userValidation->isNameLength($user->firstName)) {
            $errors[400] = 'user first name is not set';
            return null;
        }

        if (!isset($user->lastName)) {
            $errors[400] = 'user last name is not set';
            return null;
        }
        $user->lastName = filter_var($user->lastName, FILTER_SANITIZE_STRING);
        if (!$this->userValidation->isNameLength($user->lastName)) {
            $errors[400] = 'user last name should be more than 3 chars';
            return null;
        }

        if (!isset($user->email)) {
            $errors[400] = 'user email is not set';
            return null;
        }
        $user->email = filter_var($user->email, FILTER_SANITIZE_EMAIL);
        if (!$this->userValidation->isAnEmail($user->email)) {
            $errors[400] = 'user email is not valid';
            return null;
        }

        $domain = explode('@', $user->email);
        $domain = array_pop($domain);
        if (!$this->userValidation->isDomainMx($domain)) {
            $errors[400] = 'domain for the provided email is not valid';
            return null;
        }

        return $user;
    }
}
