<?php

namespace App\Models;

use App\Providers\DBConnection;
use PDO;

class Order
{
    public static function getAll($orderBy, $orderDir, $limit, $offset)
    {
        try {
            $db = DBConnection::getInstance()->getPDO();
            $query = "SELECT u.name as user_name,p.name as product_name, o.* FROM transactions o inner join users u on u.id = o.user_id inner join products p on p.id = o.product_id ORDER BY $orderBy $orderDir LIMIT :limit OFFSET :offset";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $totalQuery = "SELECT COUNT(*) FROM transactions";
            $totalStmt = $db->prepare($totalQuery);
            if ($totalStmt->execute()) {
                $total = $totalStmt->fetchColumn();
            }
            $totalPages = ceil($total / $limit);
            return [
                'orders' => $orders,
                'total' => $total,
                'totalPages' => $totalPages,
                'orderBy' => $orderBy,
                'orderDir' => $orderDir,
            ];
        } catch (\PDOException $e) {
            throw new \Exception("Error fetching roles: " . $e->getMessage());
        }
    }

    public static function getAuthUserOrders($orderBy, $orderDir, $limit, $offset)
    {
        try {
            $db = DBConnection::getInstance()->getPDO();
            $query = "SELECT u.name as user_name,p.name as product_name, o.* FROM transactions o inner join users u on u.id = o.user_id inner join products p on p.id = o.product_id where o.user_id = :auth_user_id ORDER BY $orderBy $orderDir LIMIT :limit OFFSET :offset";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':auth_user_id', $_SESSION['user_id']);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $totalQuery = "SELECT COUNT(*) FROM transactions";
            $totalStmt = $db->prepare($totalQuery);
            if ($totalStmt->execute()) {
                $total = $totalStmt->fetchColumn();
            }
            $totalPages = ceil($total / $limit);
            return [
                'orders' => $orders,
                'total' => $total,
                'totalPages' => $totalPages,
                'orderBy' => $orderBy,
                'orderDir' => $orderDir,
            ];
        } catch (\PDOException $e) {
            throw new \Exception("Error fetching roles: " . $e->getMessage());
        }
    }
}
