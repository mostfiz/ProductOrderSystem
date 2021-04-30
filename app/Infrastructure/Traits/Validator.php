<?php


namespace App\Infrastructure\Traits;

use DateTime;

trait Validator{

    /**
     * Dates validation.
     * 
     * @param array $dates A array of string representation of dates.
     * @param string $format The format that the passed in string. The same letters as for the date() can be used.
     *
     * @return bool  True if all dates are valid.
     * @access  public
     */
    public static function dates(array $dates, string $format = "Y-m-d"): bool {

        if (is_array($dates)) {

            foreach ($dates as $name => $date) {

                $d = DateTime::createFromFormat($format, $date);

                if ($d === false || $d->format($format) !== $date) {

                    return false;
                }
            }
            return true;
        }
    }

    /**
     *     public function validateOrders()
     * 
     *
     * @return bool
     * @access  public
     */
    public function validateOrders($input)
    {
        if (! isset($input['amount'])) {
            return false;
        }
        if (! isset($input['quantity'])) {
            return false;
        }
        return true;
    }

    /**
     *     public function validateUsers()
     * 
     *
     * @return bool
     * @access  public
     */
    public function validateUsers($input)
    {
        if (! isset($input['first_name'])) {
            return false;
        }
        if (! isset($input['first_name'])) {
            return false;
        }
        return true;
    }

    /**
     *     public function validateProduct()
     * 
     *
     * @return bool
     * @access  public
     */
    public function validateProduct($input)
    {
       // `product_name`,`sku`,`description`,`category`,`price`,`image_link`,`entry_by`,`entry_at`,`updated_by`,`updated_at`
        if (! isset($input['product_name'])) {
            return false;
        }
        if (! isset($input['price'])) {
            return false;
        }
        return true;
    }

    /**
     *     public function validateCategory()
     * 
     *
     * @return bool
     * @access  public
     */
    public function validateCategory($input)
    {
        //`category_name`,`entry_by`,`entry_at`
        if (! isset($input['category_name'])) {
            return false;
        }
        if (! isset($input['updated_by'])) {
            return false;
        }
        return true;
    }

    /**
     *     public function validateCategory()
     * 
     *
     * @return bool
     * @access  public
     */
    public function validateUserData($input)
    {
        //`category_name`,`entry_by`,`entry_at`
        if (! isset($input['username'])) {
            return false;
        }
        if (! isset($input['password'])) {
            return false;
        }
        return true;
    }

    
    

}
