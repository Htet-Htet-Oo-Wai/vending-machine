<?php

namespace App\Models;

use App\Providers\DBConnection;
use PDO;

class User
{
    /**
     * Get user by email.
     */
    public static function getUser($username)
    {
        try {
            $db = DBConnection::getInstance()->getPDO();
            $stmt = $db->prepare("SELECT r.name as role_name, u.* FROM users u inner join roles r on r.id = u.role_id  WHERE u.email = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Error fetching roles: " . $e->getMessage());
        }
    }

    /**
     * Get all users from the database.
     *
     * @return array
     */
    public static function getAll($orderBy, $orderDir, $limit, $offset)
    {
        try {
            $db = DBConnection::getInstance()->getPDO();
            $query = "SELECT r.name as role_name, u.* FROM users u inner join roles r on r.id = u.role_id ORDER BY $orderBy $orderDir LIMIT :limit OFFSET :offset";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $totalQuery = "SELECT COUNT(*) FROM users";
            $totalStmt = $db->prepare($totalQuery);
            if ($totalStmt->execute()) {
                $total = $totalStmt->fetchColumn();
            }
            $totalPages = ceil($total / $limit);
            return [
                'users' => $users,
                'total' => $total,
                'totalPages' => $totalPages,
                'orderBy' => $orderBy,
                'orderDir' => $orderDir,
            ];
        } catch (\PDOException $e) {
            throw new \Exception("Error fetching roles: " . $e->getMessage());
        }
    }

    /**
     * Find by id.
     */
    public static function find($id)
    {
        try {
            $db = DBConnection::getInstance()->getPDO();
            $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Error fetching roles: " . $e->getMessage());
        }
    }

    public static function create($data)
    {
        try {
            $db = DBConnection::getInstance()->getPDO();
            $stmt = $db->prepare("INSERT INTO users (name, email, password, role_id) VALUES (:name, :email, :password, :role_id)");
            $executed = $stmt->execute(['name' => $data['name'], 'email' =>  $data['email'], 'password' =>  $data['password'], 'role_id' => $data['role_id']]);
            if ($executed) {
                $lastInsertId = $db->lastInsertId();
                return [
                    'status' => 'success',
                    'message' => 'Product successfully inserted.',
                    'user_id' => $lastInsertId,
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Failed to insert user.',
                ];
            }
        } catch (\PDOException $e) {
            throw new \Exception("Error fetching roles: " . $e->getMessage());
        }
    }

    public static function update($id, $data)
    {
        try {
            $db = DBConnection::getInstance()->getPDO();
            $stmt = $db->prepare("UPDATE users SET name = :name, email = :email, role_id = :role_id WHERE id = :id");
            $stmt->execute(['id' => $data['id'], 'name' => $data['name'], 'email' => $data['email'], 'role_id' => $data['role_id']]);
        } catch (\PDOException $e) {
            throw new \Exception("Error fetching roles: " . $e->getMessage());
        }
    }

    public static function delete($id)
    {
        try {
            $db = DBConnection::getInstance()->getPDO();
            $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            throw new \Exception("Error fetching roles: " . $e->getMessage());
        }
    }
}
