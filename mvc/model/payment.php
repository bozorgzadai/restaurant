<?php

class PaymentModel
{
    public static function open_transaction($price, $userId, $authority, $invoiceHash)
    {
        $db = Db::getInstance();
        $time = date("Y-m-d H:m:s");

        $db->modify("DELETE FROM x_transaction WHERE invoice_hash=:invoiceHash AND payed=0", array(
            'invoiceHash' => $invoiceHash,
        ));

        $db->insert("INSERT INTO x_transaction (price, user_id, authority, creationTime, payed, invoice_hash) VALUES (:price, :userId, :authority, '$time' , 0, :invoiceHash)", array(
            'price' => $price,
            'userId' => $userId,
            'authority' => $authority,
            'invoiceHash' => $invoiceHash,
        ));
    }

    public static function close_transaction($reference, $invoiceHash)
    {
        $db = Db::getInstance();
        $time = date("Y-m-d H:m:s");
        $db->modify("UPDATE x_transaction SET reference=:reference, payed=:payed, paymentTime=:time WHERE invoice_hash=:invoiceHash", array(
            'payed' => 1,
            'reference' => $reference,
            'invoiceHash' => $invoiceHash,
            'time' => $time,
        ));
    }

    public static function fetch_invoice_by_hash($invoiceHash)
    {
        $db = Db::getInstance();
        $record = $db->first("SELECT * FROM x_invoice LEFT OUTER JOIN x_user ON x_invoice.user_id=x_user.user_id WHERE x_invoice.hash=:invoiceHash", array(
            'invoiceHash' => $invoiceHash,
        ));

        return $record;
    }

    public static function create_invoice($userId, $price, $startDate, $endDate, $title = null)
    {
        $db = Db::getInstance();
        $hash = generateHash(32);
        if (!isset($title)) {
            $title = 'یک فاکتور تستی';
        }
        $id = $db->insert("INSERT INTO x_invoice (user_id, price, startDate, endDate, hash, title) VALUES (:user_id, :price, :startDate, :endDate, :hash, :title)", array(
            'user_id' => $userId,
            'price' => $price,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'hash' => $hash,
            'title' => $title,
        ));

        return $id;
    }

    public static function is_invoice_payed($invoiceHash)
    {
        $db = Db::getInstance();
        $record = $db->first("SELECT * FROM x_invoice LEFT OUTER JOIN x_transaction ON x_invoice.hash=x_transaction.invoice_hash WHERE x_invoice.hash=:invoiceHash", array(
            'invoiceHash' => $invoiceHash,
        ));

        return $record['payed'];
    }

    public static function fetch_pending_invoices($userId)
    {
        $db = Db::getInstance();
        $records = $db->query("SELECT x_invoice.* FROM x_invoice LEFT OUTER JOIN x_transaction ON x_invoice.hash=x_transaction.invoice_hash WHERE x_invoice.user_id=:userId AND (x_transaction.payed IS NULL OR x_transaction.payed = 0)", array(
            'userId' => $userId,
        ));
        return $records;
    }
}