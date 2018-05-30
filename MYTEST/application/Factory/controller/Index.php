<?php
namespace app\Factory\controller;
use think\Controller as Con;
require "Factory.php";
class Index extends Con{
    public function index(){
        $uo = UserFactory::Create( 1 );
        echo( $uo->getName()."\n" );
    }
}
interface IUser {
    function getName();
}
class User implements IUser
{
    public function __construct( $id ) { }

    public function getName()
    {
        return "Jack";
    }
}
class UserFactory
{
    public static function Create( $id )
    {
        return new User( $id );
    }
}
