<?php

namespace App\Repository;

const DEFAULT_URL = 'https://pimpofpet.firebaseio.com/';
const DEFAULT_TOKEN = 'FAGswNvJdDthRMHLSjhD84t8EcWjJhKiqZk9kQNN';
const DEFAULT_PATH = '/user';

use App\Entity\Firebase;
use App\Entity\MyPetUser;
use App\Help\TokenGen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MyPetUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method MyPetUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method MyPetUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MyPetUserRepository extends ServiceEntityRepository
{
    private $users = [];
    private $firebaseUsers = null;
    private $newFirebaseUser = null;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MyPetUser::class);
    }

    public function findAll() {
        $firebase = Firebase::getInstance(DEFAULT_URL, DEFAULT_TOKEN);
        $firebaseUsers = $firebase->get(DEFAULT_PATH . '/');
        $jsonUsers = json_decode($firebaseUsers, true);

        foreach ($jsonUsers as $id => $jsonuUser) {
            $user = new MyPetUser();
            $user->setId($id);
            $user->setEmail($jsonuUser['email']);
            $user->setStatus($jsonuUser['status']);
            $user->setNom($jsonuUser['nom']);
            $user->setPrenom($jsonuUser['prenom']);

            $users[] = $user;
        }
        return $users;
    }

    public function findOne(String $id) {
        $firebase = Firebase::getInstance(DEFAULT_URL, DEFAULT_TOKEN);
        $firebaseUsers = $firebase->get(DEFAULT_PATH . '/'. $id);
        $jsonUser = json_decode($firebaseUsers, true);

        $user = new MyPetUser();
        $user->setId($id);
        $user->setEmail($jsonUser['email']);
        $user->setStatus($jsonUser['status']);
        $user->setNom($jsonUser['nom']);
        $user->setPrenom($jsonUser['prenom']);

        return $user;
    }

    public function add(MyPetUser $myPetUser) {
        $firebase = Firebase::getInstance(DEFAULT_URL, DEFAULT_TOKEN);
        $token = TokenGen::generateToken();
        $myPetUser->setId($token);
        $newFirebaseUser = [
            'email' => $myPetUser->getEmail(),
            'status' => $myPetUser->getStatus(),
            'nom' => $myPetUser->getNom(),
            'prenom' => $myPetUser->getPrenom()
        ];
        json_encode($newFirebaseUser);
        $firebase->set(DEFAULT_PATH . "/$token/" , $newFirebaseUser);

        return $myPetUser;

    }

    public function update(MyPetUser $myPetUser) {
        $firebase = Firebase::getInstance(DEFAULT_URL, DEFAULT_TOKEN);
        $newFirebaseUser = [
            'email' => $myPetUser->getEmail(),
            'status' => $myPetUser->getStatus(),
            'nom' => $myPetUser->getNom(),
            'prenom' => $myPetUser->getPrenom()
        ];
        json_encode($newFirebaseUser);
        $firebase->update(DEFAULT_PATH . "/" . $myPetUser->getId() . "/" , $newFirebaseUser);

    }

    public function delete(String $id) {
        $firebase = Firebase::getInstance(DEFAULT_URL, DEFAULT_TOKEN);

        $firebase->delete(DEFAULT_PATH . "/$id/");

        return $id;

    }

}
