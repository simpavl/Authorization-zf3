<?php
namespace Authorizationzf3\Service;
use Authorizationzf3\Entity\User;
use Zend\Authentication\Result;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
/**
 * The AuthManager service is responsible for user's login/logout and simple access
 * filtering. The access filtering feature checks whether the current visitor
 * is allowed to see the given page or not.
 */
class AuthManager
{
    /**
     * Authentication service.
     * @var Zend\Authentication\AuthenticationService
     */
    private $authService;

    /**
     * Session manager.
     * @var \Zend\Session\SessionManager
     */
    private $sessionManager;

    /**
     * Contents of the 'access_filter' config key.
     * @var array
     */
    private $config;

    /**
     * Doctrine entity manager.
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    /**
     * Constructs the service.
     */
    public function __construct($authService, $sessionManager, $config, $entityManager)
    {
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
        $this->config = $config;
        $this->entityManager = $entityManager;
    }

    /**
     * Performs a login attempt. If $rememberMe argument is true, it forces the session
     * to last for one month (otherwise the session expires on one hour).
     */
    public function login($email, $password, $rememberMe)
    {
        // Check if user has already logged in. If so, do not allow to log in
        // twice.
        if ($this->authService->getIdentity()!=null) {
            throw new \Exception('Already logged in');
        }

        // Authenticate with login/password.
        $authAdapter = $this->authService->getAdapter();
        $authAdapter->setEmail($email);
        $authAdapter->setPassword($password);
        $result = $this->authService->authenticate();
        // If user wants to "remember him", we will make session to expire in
        // one month. By default session expires in 1 hour (as specified in our
        // config/global.php file).
        if ($result->getCode()==Result::SUCCESS && $rememberMe) {
            // Session cookie will expire in 1 month (30 days).
            $this->sessionManager->rememberMe(60*60*24*30);
        }

        return $result;
    }

    /**
     * Performs user logout.
     */
    public function logout()
    {
        // Allow to log out only when user is logged in.
        if ($this->authService->getIdentity()==null) {
            throw new \Exception('The user is not logged in');
        }
        // Remove identity from session.
        $this->authService->clearIdentity();

    }


    /**
     * This is a simple access control filter. It is able to restrict unauthorized
     * users to visit certain pages.
     *
     * This method uses the 'access_filter' key in the config file and determines
     * whenther the current visitor is allowed to access the given controller action
     * or not. It returns true if allowed; otherwise false.
     */
    public function filterAccess($controllerName, $actionName)
    {
        $acl = new Acl();
        $allResources = [];
        if(isset($this->config['authcontroller'])){
          if(key($this->config['authcontroller']) == $controllerName){
            echo("hello");
            return true;
          }
        else return false;
        }
        if (isset($this->config['controllers'][$controllerName])) {
        $items = $this->config['controllers'][$controllerName];
        foreach ($items as $role => $resources) {
            $role = new Role($role);
            $acl->addRole($role);
            $allResources = array_merge($resources, $allResources);
            foreach ($resources as $resource){
                if(!$acl ->hasResource($resource))
                    $acl->addResource(new Resource($resource));
            }
            foreach($allResources as $resource){
                $acl->allow($role,$resource);
            }
        }
        if($this->authService->hasIdentity())
        {
            $userident = $this->authService->getIdentity();
            $userentity = $this->entityManager->getRepository(User::class)->findOneByEmail($userident);
            $userRole = $userentity->getRoles();

            $userRole = $userRole[0]->getRoleId();
            if($acl->isAllowed($userRole,$actionName))
            {
                return true;
            }
            else return false;
        }
    }



        // In restrictive mode, we forbid access for unauthorized users to any
        // action not listed under 'access_filter' key (for security reasons).
        /*if ($mode=='restrictive' && !$this->authService->hasIdentity())
            return false;*/

        // Permit access to this page.
        //return true;
        return false;
    }
}
