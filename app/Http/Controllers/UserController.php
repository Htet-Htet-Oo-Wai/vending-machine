<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a paginated list of all products for administrators.
     *
     * @return void
     */
    public function index()
    {
        $this->checkRole(config('constants.admin_role'));
        $limit = 2;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $_SESSION['current_page'] = $page;
        $offset = ($page - 1) * $limit;
        $orderBy = isset($_GET['sort']) ? $_GET['sort'] : 'id';
        $orderDir = isset($_GET['dir']) && in_array($_GET['dir'], ['asc', 'desc']) ? $_GET['dir'] : 'asc';

        $users = User::getAll($orderBy, $orderDir, $limit, $offset);
        view('admin/users/index', $users);
    }

    /**
     * Show the create product form.
     *
     * @return void
     */
    public function create()
    {
        $this->checkRole(config('constants.admin_role'));
        $roles = Role::getAll();
        view('admin/users/create', compact('roles'));
    }

    /**
     * Store a newly created product.
     *
     * @return void
     */
    public function store()
    {
        $this->checkRole(config('constants.admin_role'));
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new CreateUserRequest($_POST);
            if (!$request->validate()) {
                $_SESSION['errors'] = $request->errors();
                header('Location: /admin/users/create');
                exit;
            }
            $data = $request->sanitized();
            $result = User::create($data);
            if ($result['status'] == 'success') {
                header('Location: /admin/users');
                exit();
            }
        }
    }

    /**
     * Show the edit product form.
     *
     * @param int $id
     * @return void
     */
    public function edit($id)
    {
        $this->checkRole(config('constants.admin_role'));
        $roles = Role::getAll();
        $user = User::find($id);
        view('admin/users/edit', compact(['user', 'roles']));
    }

    /**
     * Update an existing product.
     *
     * @param int $id
     * @return void
     */
    public function update($id)
    {
        $this->checkRole(config('constants.admin_role'));
        $request = new UpdateUserRequest($_POST);
        if (!$request->validate()) {
            $_SESSION['errors'] = $request->errors();
            header('Location: /admin/users/create');
            exit;
        }
        $data = $request->sanitized();
        User::update($id, $data);
        header("Location: /admin/users");
    }

    /**
     * Delete a product.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        $this->checkRole(config('constants.admin_role'));
        User::delete($id);
        header('Location: /admin/users');
        exit;
    }
}
