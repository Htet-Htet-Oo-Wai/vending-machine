<?php

namespace App\Models;

use App\Providers\DBConnection;
use PDO;

class Role
{
    /**
     * Get all roles from the database.
     *
     * @return array
     */
    public static function getAll()
    {
        try {
            $db = DBConnection::getInstance()->getPDO();
            $query = "SELECT * FROM roles";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $roles;
        } catch (\PDOException $e) {
            // Log the exception or handle it as needed
            throw new \Exception("Error fetching roles: " . $e->getMessage());
        }
    }
}
