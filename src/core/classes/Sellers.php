<?php
class Sellers
{
    private $pdo_db;

    public function __construct($pdo)
    {
        $this->pdo_db = $pdo;
    }

    /**
     * retrieves all data from the `sellers` table
     * 
     * @return array associative array of all rows of `sellers`
     */
    public function getSellers()
    {
        $sql = "SELECT * FROM sellers";
        $stmt = $this->pdo_db->prepare($sql);

        try
        {
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch(PDOException $e){ error_log($e->getMessage()); }
    }

    /**
     * inserts a row to the `sellers` table
     * 
     * @param string $name the name of the seller
     * @param string $btn_color the desired color of the sellers button
     * @param string $txt_color the desired color of the text in the sellers button
     * 
     * @return int the id of the inserted row
     */
    public function insertSeller($name, $btn_color, $txt_color)
    {
        $sql =
        "INSERT INTO `sellers` (`name`, `btn_color`, `txt_color`)
         VALUES(:name, :btn_color, :txt_color)";
        $stmt = $this->pdo_db->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':btn_color', $btn_color);
        $stmt->bindParam(':txt_color', $txt_color);

        try
        {
            $stmt->execute();
            return $this->pdo_db->lastInsertID();
        }
        catch(PDOException $e){ error_log($e->getMessage()); }
    }

    /**
     * retrieves the row from the `sellers` table with the passed id
     * 
     * @param int $id the id of the seller
     * 
     * @return array an associative array of the retrieved row
     */
    public function getSeller($id)
    {
        $sql = "SELECT * FROM `sellers` WHERE id = :id";
        $stmt = $this->pdo_db->prepare($sql);
        $stmt->bindParam(':id', $id);

        try
        {
            $stmt->execute();
            return $stmt->fetch();
        }
        catch(PDOException $e){ error_log($e->getMessage()); }
    }
}