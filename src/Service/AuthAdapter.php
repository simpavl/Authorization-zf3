<?php
namespace Authorizationzf3\Service;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;
use Authorizationzf3\Entity\User;

/**
 * Это адаптер, используемый для аутентификации пользователя. Он принимает логин (адрес эл. почты)
 * и пароль, и затем проверяет, есть ли в базе данных пользователь с такими учетными данными.
 * Если такой пользователь существует, сервис возвращает его личность (эл. адрес). Личность
 * сохраняется в сессии и может быть извлечена позже с помощью помощника представления Identity,
 * предоставляемого ZF3.
 */
class AuthAdapter implements AdapterInterface
{
    /**
     * E-mail пользователя.
     * @var string
     */
    private $email;

    /**
     * Пароль.
     * @var string
     */
    private $password;

    /**
     * Менеджер сущностей.
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Конструктор.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Задает эл. адрес пользователя.
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Устанавливает пароль.
     */
    public function setPassword($password)
    {
        $this->password = (string)$password;
    }

    /**
     * Выполняет попытку аутентификации.
     */
    public function authenticate()
    {
        // Проверяем, есть ли в базе данных пользователь с таким адресом.
        $user = $this->entityManager->getRepository(User::class)
            ->findOneByEmail($this->email);

        // Если такого пользователя нет, возвращаем статус 'Identity Not Found'.
        if ($user == null) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                ['Invalid credentials.']);
        }

        // Если пользователь с таким адресом существует, необходимо проверить, активен ли он.
        // Неактивные пользователи не могут входить в систему.
        if ($user->getStatusAsString()=='Retired') {
            return new Result(
                Result::FAILURE,
                null,
                ['User is retired.']);
        }

        // Теперь необходимо вычислить хэш на основе введенного пользователем пароля и сравнить его
        // с хэшем пароля из базы данных.
        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        if ($bcrypt->verify($this->password, $passwordHash)) {
            // Отлично! Хэши паролей совпадают. Возвращаем личность пользователя (эл. адрес) для
            // хранения в сессии с целью последующего использования.
            return new Result(
                Result::SUCCESS,
                $this->email,
                ['Authenticated successfully.']);
        }

        // Если пароль не прошел проверку, возвращаем статус ошибки 'Invalid Credential'.
        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Invalid credentials.']);
    }
}
