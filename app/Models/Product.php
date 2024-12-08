<?php

namespace App\Models;

use App\Providers\DBConnection;
use Exception;
use PDO;

class Product
{
    public static function getAll($orderBy, $orderDir, $limit, $offset)
    {
        try {
            $db = DBConnection::getInstance()->getPDO();
            $query = "SELECT * FROM products ORDER BY $orderBy $orderDir LIMIT :limit OFFSET :offset";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $totalQuery = "SELECT COUNT(*) FROM products";
            $totalStmt = $db->prepare($totalQuery);
            if ($totalStmt->execute()) {
                $total = $totalStmt->fetchColumn();
            }
            $totalPages = ceil($total / $limit);
            return [
                'products' => $products,
                'total' => $total,
                'totalPages' => $totalPages,
                'orderBy' => $orderBy,
                'orderDir' => $orderDir,
            ];
        } catch (\PDOException $e) {
            throw new \Exception("Error fetching roles: " . $e->getMessage());
        }
    }

    public static function find($id)
    {
        try {
            $db = DBConnection::getInstance()->getPDO();
            $stmt = $db->prepare("SELECT * FROM products WHERE id = :id");
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
            $stmt = $db->prepare("INSERT INTO products (name, price, quantity_available) VALUES (?, ?, ?)");
            $executed = $stmt->execute([$data['name'], $data['price'], $data['quantity_available']]);
            if ($executed) {
                $lastInsertId = $db->lastInsertId();
                return [
                    'status' => 'success',
                    'message' => 'Product successfully inserted.',
                    'product_id' => $lastInsertId,
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Failed to insert product.',
                ];
            }
        } catch (\PDOException $e) {
            throw new \Exception("Error fetching roles: " . $e->getMessage());
        }
    }

    public static function update($id, $name, $price, $quantity)
    {
        try {
            $db = DBConnection::getInstance()->getPDO();
            $stmt = $db->prepare("UPDATE products SET name = :name, price = :price, quantity_available = :quantity WHERE id = :id");
            $stmt->execute(['id' => $id, 'name' => $name, 'price' => $price, 'quantity' => $quantity]);
        } catch (\PDOException $e) {
            throw new \Exception("Error fetching roles: " . $e->getMessage());
        }
    }

    public static function delete($id)
    {
        try {
            $db = DBConnection::getInstance()->getPDO();
            $stmt = $db->prepare("DELETE FROM products WHERE id = :id");
            $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            throw new \Exception("Error fetching roles: " . $e->getMessage());
        }
    }

    public static function purchase()
    {
        try {
            $db = DBConnection::getInstance()->getPDO();
            $db->beginTransaction();
            foreach ($_SESSION['cart'] as $item) {
                $stmt = $db->prepare("SELECT * FROM products WHERE id = :product_id FOR UPDATE");
                $stmt->execute(['product_id' => $item['id']]);
                $product = $stmt->fetch();

                if (!$product) {
                    $errors[] = 'Product not found.';
                    return [
                        'status' => false,
                        'errors' => $errors,
                    ];
                }

                if ($product['quantity_available'] < $item['quantity']) {
                    $errors[] = $item['name'] . ' is insufficient stock available.';
                    return [
                        'status' => false,
                        'errors' => $errors,
                    ];
                }

                $total_price = (int)$item['quantity'] * (int)$item['price'];
                $stmt = $db->prepare("INSERT INTO transactions (user_id, product_id, quantity, total_price) VALUES (:user_id, :product_id, :quantity, :total_price)");
                $stmt->execute(['user_id' => $_SESSION['user_id'], 'product_id' => $item['id'], 'quantity' => $item['quantity'], 'total_price' => $total_price]);
                $update_stmt = $db->prepare("UPDATE products SET quantity_available = quantity_available - :quantity WHERE id = :id");
                $update_stmt->execute([
                    'id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);
            }
            $db->commit();
            return [
                'status' => true,
                'errors' => [],
            ];
        } catch (Exception $e) {
            $db->rollBack();
            $errors[] = $e->getMessage();
            return [
                'status' => false,
                'errors' => $errors,
            ];
        }
    }
}
