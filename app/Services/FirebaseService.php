<?php


namespace App\Services;


use Kreait\Firebase\Contract\Database;


class FirebaseService
{
    protected $fireDatabase;
    public function __construct(Database $fireDatabase){
        $this->fireDatabase = $fireDatabase;
    }
    public function updateVersion($key){
        $this->fireDatabase->getReference($key)->set(uniqid());
    }
}
