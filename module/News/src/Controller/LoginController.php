<?php

declare(strict_types=1);

namespace News\Controller;

use Laminas\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Adapter\Adapter;
use Laminas\Authentication\Result;
use Laminas\Session\SessionManager;
use Laminas\Authentication\AuthenticationService;

use News\Form\Auth\LoginForm;
use News\Model\Table\UsersTable;
 

class LoginController extends AbstractActionController
{
    private $usersTable;
    private $adapter;

    public function __construct(Adapter $adapter, UsersTable $usersTable)
    {
        $this->usersTable = $usersTable;
        $this->adapter = $adapter;
    }

    public function indexAction()
    {
        $auth = new AuthenticationService();
        if($auth->hasIdentity()) {
            return $this->redirect()->toRoute('home'); // Acc realdy navigation to home
        }

        $loginForm = new LoginForm();
        $request = $this->getRequest();

        if($request->isPost()) {
            $formData = $request->getPost()->toArray();
            $loginForm->setInputFilter($this->usersTable->getLoginFormFilter());
            $loginForm->setData($formData);

            if($loginForm->isValid()) {
                $authAdapter = new CredentialTreatmentAdapter($this->adapter);
                $authAdapter->setTableName($this->usersTable->getTable())
                            ->setIdentityColumn('email')
                            ->setCredentialColumn('password')
                            ->getDbSelect()->where(['active' => 1]);

                # data from loginForm
                $data = $loginForm->getData();
                $authAdapter->setIdentity($data['email']);


                # password hashing class
                $hash = new Bcrypt();

                # well let us use the email address from the form to retrieve data for this user
                $info = $this->usersTable->fetchAccountByEmail($data['email']);

                # now compare password from form input with that already in the table
                if($hash->verify($data['password'], $info->getPassword())) {
                    $authAdapter->setCredential($info->getPassword());
                } else {
                    $authAdapter->setCredential(''); # why? to gracefully handle errors
                }

                $authResult = $auth->authenticate($authAdapter);

                switch ($authResult->getCode()) {
                    case Result::FAILURE_IDENTITY_NOT_FOUND:
                        $this->flashMessenger()->addErrorMessage('Địa chỉ email không xác định!');
                        return $this->redirect()->refresh(); # refresh the page to show error
                        break;

                    case Result::FAILURE_CREDENTIAL_INVALID:
                        $this->flashMessenger()->addErrorMessage('Mật khẩu không đúng!');
                        return $this->redirect()->refresh(); # refresh the page to show error
                        break;
                        
                    case Result::SUCCESS:
                        if($data['recall'] == 1) {
                            $ssm = new SessionManager();
                            $ttl = 1814400; # time for session to live
                            $ssm->rememberMe($ttl);
                        }
                        $storage = $auth->getStorage();
                        $storage->write($authAdapter->getResultRowObject(null, ['created_at', 'updated_at']));
                        # let us now create the profile route and we will be done
                        return $this->redirect()->toRoute(
                            'profile', 
                            [
                                'id' => $info->getId(),
                                'username' => mb_strtolower($info->getUsername())
                            ]
                        );
                        break;      
                    default:
                        $this->flashMessenger()->addErrorMessage('Quá trình xác thực đã thất bại. Thử lại');
                        return $this->redirect()->refresh(); # refresh the page to show error
                        break;
                }
            }
        }


        return (new ViewModel([ 'form' => $loginForm ]))->setTemplate('news/auth/login');
    }
}
