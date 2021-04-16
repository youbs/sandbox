<?php

namespace App\DTO;

/**
 * You can add validation rules to check if one or the other is set.
 */
class CreateOrder
{
    public $item;
    public $customerId;
    public $customerName;
}