<?php
class WishList
{
    private $pdo_db;

    public function __construct($pdo)
    {
        $this->pdo_db = $pdo;
    }

    /**
     * retrieves all data in the `wish_list` table
     * 
     * @return arrray associative array of all rows of `wish_list`
     */
    public function getItems()
    {
        $sql =
        "SELECT * FROM `wish_list`
         ORDER BY `preference`";
        $stmt = $this->pdo_db->prepare($sql);

        try
        {
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch(PDOException $e)
        {
            error_log($e->getMessage());
        }
    }

    /**
     * retrieves all columns of the specified row of the `wish_list` table
     * 
     * @param int $id the id of the wish_list item
     * 
     * @return array associative array of the retrieved `wish_list` item
     */
    public function getItem($id)
    {
        $sql = "SELECT * FROM `wish_list` WHERE `id` = :id";
        $stmt = $this->pdo_db->prepare($sql);
        $stmt->bindParam(':id', $id);

        try
        {
            $stmt->execute();
            return $stmt->fetch();
        }
        catch(PDOException $e)
        {
            error_log($e->getMessage());
        }
    }

    /**
     * sets `wish_list`.`purchased` to true for the specified row
     * 
     * @param int $id the id of the wish_list item
     */
    public function claimItem($id)
    {
        $sql = "UPDATE `wish_list` SET `purchased` = 1 WHERE `id` = :id";
        $stmt = $this->pdo_db->prepare($sql);
        $stmt->bindParam(':id', $id);

        try
        {
            $stmt->execute();
        }
        catch(PDOException $e)
        {
            error_log($e->getMessage());
        }
    }

    /**
     * inserts a row to the `wish_list` table
     * 
     * @param string $name the name of the item
     * @param string $link [optional] the url of the item if given (default: `null`)
     * @param int $seller_id [optional] the id of the seller if given (default: `null`)
     */
    public function insertItem($name, $link, $seller_id)
    {
        $sql_maxPref = "SELECT MAX(`preference`) FROM `wish_list`";
        $stmt_maxPref = $this->pdo_db->prepare($sql_maxPref);
        try
        {
            $stmt_maxPref->execute();
            $maxPref = $stmt_maxPref->fetch();
        }
        catch(PDOException $e){ error_log($e->getMessage()); }

        $preference = $maxPref['MAX(`preference`)'] + 1;

        $sql_insert =
        "INSERT INTO `wish_list` (`name`, `link`, `seller_id`, `preference`)
         VALUES (:name, :link, :seller_id, $preference)";
        $stmt_insert = $this->pdo_db->prepare($sql_insert);
        $stmt_insert->bindParam(':name', $name);
        $stmt_insert->bindParam(':link', $link);
        $stmt_insert->bindParam(':seller_id', $seller_id);

        try
        {
            $stmt_insert->execute();
        }
        catch(PDOException $e)
        {
            error_log($e->getMessage());
        }
    }

    /**
     * updates a row in the `wish_list` table
     * 
     * @param int $id the id of the item
     * @param string $name the name of the item
     * @param string $link [optional] the url of the item if given (default: `null`)
     * @param int $seller_id [optional] the id of the seller if given (default: `null`)
     */
    public function editItem($id, $name, $link, $seller_id)
    {
        $sql =
        "UPDATE `wish_list` SET
         `name` = :name,
         `link` = :link,
         `seller_id` = :seller_id
         WHERE `id` = :id";
        $stmt = $this->pdo_db->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':link', $link);
        $stmt->bindParam(':seller_id', $seller_id);
        $stmt->bindParam(':id', $id);

        try
        {
            $stmt->execute();
        }
        catch(PDOException $e)
        {
            error_log($e->getMessage());
        }
    }

    /**
     * deletes a row from the `wish_list` table
     * 
     * @param int $id the id of the item
     */
    public function deleteItem($id)
    {
        $sql = "DELETE FROM `wish_list` WHERE `id` = :id";
        $stmt = $this->pdo_db->prepare($sql);
        $stmt->bindParam(':id', $id);

        try
        {
            $stmt->execute();
        }
        catch(PDOException $e)
        {
            error_log($e->getMessage());
        }
    }

    /**
     * increases the `preference` value of an item by 1; decreases by 1 the `preference` value of the item that originally had the new prefernce value of the specified item
     * 
     * @param int $id the id of the item to change
     * @param int $currentPreference the original preference value of the item (which will be increased by 1)
     */
    public function itemPreferenceUp($id, $currentPreference)
    {
        $newPreference = $currentPreference + 1;

        $sql_decreasePref =
        "UPDATE `wish_list` SET
         `preference` = :currentPreference
         WHERE `preference` = :newPreference";
        $stmt_decreasePref = $this->pdo_db->prepare($sql_decreasePref);
        $stmt_decreasePref->bindParam(':currentPreference', $currentPreference);
        $stmt_decreasePref->bindParam(':newPreference', $newPreference);

        try{ $stmt_decreasePref->execute(); }
        catch(PDOException $e){ error_log($e->getMessage()); }

        $sql_increasePref =
        "UPDATE `wish_list` SET
         `preference` = :newPreference
         WHERE `id` = :id";
        $stmt_increasePref = $this->pdo_db->prepare($sql_increasePref);
        $stmt_increasePref->bindParam(':newPreference', $newPreference);
        $stmt_increasePref->bindParam(':id', $id);

        try{ $stmt_increasePref->execute(); }
        catch(PDOException $e){ error_log($e->getMessage()); }
    }

    /**
     * decreases the `preference` value of an item by 1; increases by 1 the `preference` value of the item that originally had the new prefernce value of the specified item
     * 
     * @param int $id the id of the item to change
     * @param int $currentPreference the original preference value of the item (which will be decreased by 1)
     */
    public function itemPreferenceDown($id, $currentPreference)
    {
        $newPreference = $currentPreference - 1;

        $sql_increasePref =
        "UPDATE `wish_list` SET
         `preference` = :currentPreference
         WHERE `preference` = :newPreference";
        $stmt_increasePref = $this->pdo_db->prepare($sql_increasePref);
        $stmt_increasePref->bindParam(':currentPreference', $currentPreference);
        $stmt_increasePref->bindParam(':newPreference', $newPreference);

        try{ $stmt_increasePref->execute(); }
        catch(PDOException $e){ error_log($e->getMessage()); }

        $sql_decreasePref =
        "UPDATE `wish_list` SET
         `preference` = :newPreference
         WHERE `id` = :id";
        $stmt_decreasePref = $this->pdo_db->prepare($sql_decreasePref);
        $stmt_decreasePref->bindParam(':newPreference', $newPreference);
        $stmt_decreasePref->bindParam(':id', $id);

        try{ $stmt_decreasePref->execute(); }
        catch(PDOException $e){ error_log($e->getMessage()); }
    }
}