<?php
namespace App\Controllers;

use Pangio\Core\Application\View;
use Pangio\Core\System\Security;
use Pangio\Core\System\Session;
use Pangio\Core\Http\Request;
use App\Models\UserModel;

class Users {
    /**
     * @return void
     */
    public function index(): void {
        $data = [
            'title' => trans('users.titles.index'),
            'data' => UserModel::all(['deleted' => 'false'])
        ];

        echo View::render('templates/header', $data) .
             View::render('users/index', $data) .
             View::render('templates/footer');
    }

    /**
     * @return void
     */
    public function create(): void {
        $data = [
            'title' => trans('users.titles.create'),
            'redirect' => Session::get('variableStorage') ?? []
        ];

        $requiredFields = [
            'username',
            'email',
            'password'
        ];

        if (Request::method() === 'POST' && Request::validate($requiredFields)) {
            $input = [
                'username' => esc(Request::post('username')),
                'email' => esc(Request::post('email')),
                'password' => Security::hashPassword(Request::post('password'))
            ];

            if (!$this->isUsernameUnique($input['username'])) {
                Session::setFlashMessage('error', trans('messages.error.usernameUnique'));
                Session::set('variableStorage', [
                    'username' => $input['username'],
                    'email' => $input['email']
                ]);

                redirect('create-user');
            }

            if (!$this->isEmailUnique($input['email'])) {
                Session::setFlashMessage('error', trans('messages.error.emailUnique'));
                Session::set('variableStorage', [
                    'username' => $input['username'],
                    'email' => $input['email']
                ]);

                redirect('create-user');
            }

            if (UserModel::insert($input)) {
                Session::setFlashMessage('success', trans('messages.success.save'));
            } else {
                Session::setFlashMessage('error', trans('messages.error.save'));
            }

            redirect('users');
        }

        echo View::render('templates/header', $data) .
             View::render('users/create', $data) .
             View::render('templates/footer');
    }

    /**
     * @param int $id
     * @return void
     */
    public function show(int $id): void {
        $data = [
            'title' => trans('users.titles.show'),
            'data' => UserModel::find($id)
        ];

        echo View::render('templates/header', $data) .
             View::render('users/show', $data) .
             View::render('templates/footer');
    }

    /**
     * @param int $id
     * @return void
     */
    public function update(int $id): void {
        $data = [
            'title' => trans('users.titles.update'),
            'data' => UserModel::find($id)
        ];

        $requiredFields = [
            'username',
            'email'
        ];

        if (Request::method() === 'POST' && Request::validate($requiredFields)) {
            $input = [
                'username' => esc(Request::post('username')),
                'email' => esc(Request::post('email'))
            ];

            if (!empty(Request::post('password'))) {
                $input['password'] = Security::hashPassword(Request::post('password'));
            }

            if (!$this->isUsernameUnique($input['username'], $id)) {
                Session::setFlashMessage('error', trans('messages.error.usernameUnique'));
                Session::set('variableStorage', [
                    'username' => $input['username'],
                    'email' => $input['email']
                ]);

                redirect("update-user/$id");
            }

            if (!$this->isEmailUnique($input['email'], $id)) {
                Session::setFlashMessage('error', trans('messages.error.emailUnique'));
                Session::set('variableStorage', [
                    'username' => $input['username'],
                    'email' => $input['email']
                ]);

                redirect("update-user/$id");
            }

            if (UserModel::insert($input)) {
                Session::setFlashMessage('success', trans('messages.success.save'));
            } else {
                Session::setFlashMessage('error', trans('messages.error.save'));
            }

            redirect('users');
        }

        echo View::render('templates/header', $data) .
             View::render('users/update', $data) .
             View::render('templates/footer');
    }

    /**
     * @param string $username
     * @param int $id
     * @return bool
     */
    private function isUsernameUnique(string $username, int $id = 0) :bool {
        $users = UserModel::all(['username' => $username]);

        foreach ($users as $user) {
            if ((int) $user['id'] !== $id) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $email
     * @param int $id
     * @return bool
     */
    private function isEmailUnique(string $email, int $id = 0) :bool {
        $users = UserModel::all(['email' => $email]);

        foreach ($users as $user) {
            if ((int) $user['id'] !== $id) {
                return false;
            }
        }

        return true;
    }
}